import React, { useEffect, useState, useRef } from 'react';
import Navbar from '../components/Navbar';
import Hero from '../components/Hero';
import MovieSection from '../components/MovieSection';
import Footer from '../components/Footer';
import axios from 'axios';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const LandingPage = () => {
    const [movies, setMovies] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');
    const mainRef = useRef(null);

    useEffect(() => {
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
    }, [searchQuery]);

    const handleSearch = (query) => {
        setSearchQuery(query);
    };

    useEffect(() => {
        if (!loading && movies.length > 0) {
            // GSAP Animations for sections
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
        }
    }, [loading, movies]);

    return (
        <div className="min-h-screen bg-bg-dark text-white">
            <Navbar onSearch={handleSearch} />
            <main ref={mainRef}>
                <Hero movie={movies[0]} />
                <div className="container mx-auto px-4 py-8 space-y-24">
                    {loading ? (
                        <div className="flex justify-center py-20">
                            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-primary"></div>
                        </div>
                    ) : (
                        <>
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

export default LandingPage;
