import React from 'react';
import MovieCard from './MovieCard';
import { ChevronRight } from 'lucide-react';

const MovieSection = ({ title, movies = [] }) => {
    return (
        <section className="space-y-6 reveal-section">
            <div className="flex items-center justify-between">
                <div className="flex items-center gap-4">
                    <h2 className="text-3xl font-black text-white">{title}</h2>
                    <div className="h-1 w-12 bg-brand-primary rounded-full mt-1" />
                </div>
                <button className="flex items-center gap-1 text-sm font-bold text-white/50 hover:text-brand-primary transition-colors uppercase tracking-widest">
                    <span>View All</span>
                    <ChevronRight className="w-4 h-4" />
                </button>
            </div>

            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                {movies.map((movie, index) => (
                    <MovieCard key={movie.id || index} {...movie} />
                ))}
            </div>
        </section>
    );
};

export default MovieSection;
