import React from 'react';
import { Star, Play, Plus } from 'lucide-react';
import { motion } from 'framer-motion';

const MovieCard = ({ title, rating, year, image, genres = [] }) => {
    return (
        <motion.div
            whileHover={{ scale: 1.05 }}
            className="group relative bg-bg-card rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all"
        >
            {/* Image Container */}
            <div className="aspect-[2/3] relative overflow-hidden">
                <img
                    src={image || "https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=2025&auto=format&fit=crop"}
                    alt={title}
                    className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />

                {/* Overlay on Hover */}
                <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <button className="bg-brand-primary p-3 rounded-2xl hover:bg-brand-secondary transition-colors shadow-lg">
                        <Play className="w-6 h-6 fill-white text-white" />
                    </button>
                    <button className="bg-white/20 backdrop-blur-md p-3 rounded-2xl hover:bg-white/30 transition-colors">
                        <Plus className="w-6 h-6 text-white" />
                    </button>
                </div>

                {/* Rating Badge */}
                <div className="absolute top-4 left-4 flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-1 rounded-xl border border-white/10">
                    <Star className="w-3 h-3 fill-brand-primary text-brand-primary" />
                    <span className="text-[10px] font-bold text-white">{rating}</span>
                </div>
            </div>

            {/* Info Container */}
            <div className="p-5 space-y-1">
                <div className="flex items-center justify-between">
                    <span className="text-[10px] font-black uppercase tracking-widest text-brand-primary">{year}</span>
                    <span className="text-[10px] font-bold text-white/40 uppercase tracking-widest">4K Ultra HD</span>
                </div>
                <h3 className="text-lg font-bold text-white truncate group-hover:text-brand-primary transition-colors">{title}</h3>
                <div className="flex items-center gap-2 overflow-hidden">
                    {genres.slice(0, 3).map((genre) => (
                        <span key={genre} className="text-[10px] text-white/50 whitespace-nowrap">{genre}</span>
                    ))}
                </div>
            </div>
        </motion.div>
    );
};

export default MovieCard;
