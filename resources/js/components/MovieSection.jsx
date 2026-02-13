import React from 'react';
import MovieCard from './MovieCard';
import { ChevronRight } from 'lucide-react';

const MovieSection = ({ title, movies = [] }) => {
    return (
        <section className="reveal-section space-y-6">
            <div className="flex items-center justify-between">
                <h2 className="text-2xl font-black uppercase tracking-tight flex items-center gap-3">
                    <div className="w-1.5 h-8 bg-brand-primary rounded-full shadow-[0_0_15px_rgba(229,9,20,0.5)]" />
                    {title}
                </h2>
                <div className="h-px flex-1 mx-8 bg-gradient-to-r from-white/10 to-transparent" />
                <button className="flex items-center gap-1 text-sm font-bold text-white/50 hover:text-brand-primary transition-colors uppercase tracking-widest">
                    <span>View All</span>
                    <ChevronRight className="w-4 h-4" />
                </button>
            </div>

            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 sm:gap-6">
                {movies.map((movie, index) => (
                    <MovieCard key={movie.id || index} {...movie} />
                ))}
            </div>
        </section>
    );
};

export default MovieSection;
