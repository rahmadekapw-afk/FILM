import React, { useEffect, useState, useRef } from 'react';
import Navbar from '../components/Navbar';
import Hero from '../components/Hero';
import MovieSection from '../components/MovieSection';
import Footer from '../components/Footer';
import axios from 'axios';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useNavigate } from 'react-router-dom';

gsap.registerPlugin(ScrollTrigger);

const UserHome = () => {
    const [movies, setMovies] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');
    const [user, setUser] = useState(null);
    const mainRef = useRef(null);
    const navigate = useNavigate();

    useEffect(() => {
        // Check if user is logged in
        const storedUser = localStorage.getItem('user');
        if (!storedUser) {
            navigate('/login');
            return;
        }
        setUser(JSON.parse(storedUser));

        setLoading(true);
        axios.get('/api/movies', {
            params: { search: searchQuery }
        })
            .then(response => {
                setMovies(response.data);
                setLoading(false);
            })
            .catch(error => {
                console.error("Error fetching movies:", error);
                setLoading(false);
            });
    }, [searchQuery, navigate]);

    const handleSearch = (query) => {
        setSearchQuery(query);
    };

    useEffect(() => {
        if (!loading && movies.length > 0) {
            const sections = gsap.utils.toArray('.reveal-section');
            sections.forEach((section) => {
                gsap.fromTo(section,
                    { opacity: 0, y: 50 },
                    {
                        opacity: 1,
                        y: 0,
                        duration: 1,
                        scrollTrigger: {
                            trigger: section,
                            start: "top 85%",
                            toggleActions: "play none none reverse"
                        }
                    }
                );
            });
            ScrollTrigger.refresh();
        }
    }, [loading, movies]);

    return (
        <div className="min-h-screen bg-bg-dark text-white">
            <Navbar onSearch={handleSearch} user={user} />
            <main ref={mainRef}>
                <Hero movie={movies[0]} user={user} />
                <div className="container mx-auto px-4 py-8 space-y-24">
                    {loading ? (
                        <div className="flex justify-center py-20">
                            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-primary"></div>
                        </div>
                    ) : (
                        <>
                            <MovieSection title="Recommended for You" movies={[...movies].sort(() => 0.5 - Math.random())} />
                            <MovieSection title="Trending Now" movies={movies} />
                            <MovieSection title="Top Rated" movies={[...movies].sort((a, b) => b.rating - a.rating)} />
                            <MovieSection title="Recently Added" movies={[...movies].reverse()} />
                        </>
                    )}
                </div>
            </main>
            <Footer />
        </div>
    );
};

export default UserHome;
