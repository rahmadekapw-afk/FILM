import React, { useEffect, useState } from 'react';
import Navbar from '../components/Navbar';
import Hero from '../components/Hero';
import MovieSection from '../components/MovieSection';
import Footer from '../components/Footer';
import axios from 'axios';

const LandingPage = () => {
    const [movies, setMovies] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        axios.get('/api/movies')
            .then(response => {
                setMovies(response.data);
                setLoading(false);
            })
            .catch(error => {
                console.error("Error fetching movies:", error);
                setLoading(false);
            });
    }, []);

    return (
        <div className="min-h-screen bg-bg-dark text-white">
            <Navbar />
            <main>
                <Hero movie={movies[0]} />
                <div className="container mx-auto px-4 py-8 space-y-12">
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
