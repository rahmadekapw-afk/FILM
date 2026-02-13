import React, { useEffect, useRef } from 'react';
import { Play, Info, Star } from 'lucide-react';
import { motion } from 'framer-motion';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Magnetic from './Magnetic';

const Hero = ({ movie, user }) => {
    const heroImgRef = useRef(null);

    useEffect(() => {
        if (heroImgRef.current) {
            gsap.to(heroImgRef.current, {
                yPercent: 30,
                ease: "none",
                scrollTrigger: {
                    trigger: ".hero-container",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                }
            });
        }
    }, [movie]);

    if (!movie) return <div className="h-[90vh] bg-bg-dark" />;

    return (
        <section className="hero-container relative h-[90vh] w-full flex items-center overflow-hidden">
            {/* Background Backdrop */}
            <div className="absolute inset-0 z-0">
                <img
                    ref={heroImgRef}
                    src={movie.image || "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=2070&auto=format&fit=crop"}
                    alt={movie.title}
                    className="w-full h-[130%] object-cover absolute top-0 left-0"
                />
                <div className="absolute inset-0 bg-gradient-to-r from-bg-dark via-bg-dark/60 to-transparent" />
                <div className="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-bg-dark to-transparent" />
            </div>

            <div className="container mx-auto px-4 md:px-6 relative z-10 pt-20">
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                    className="max-w-2xl space-y-6"
                >
                    <div className="flex flex-col gap-4">
                        {user && (
                            <motion.p
                                initial={{ opacity: 0, x: -20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.5 }}
                                className="text-brand-primary font-black uppercase tracking-[0.3em] text-sm"
                            >
                                Welcome back, {user.name}
                            </motion.p>
                        )}
                        <div className="flex items-center gap-2 bg-white/10 backdrop-blur-md w-fit px-3 py-1 rounded-full border border-white/20">
                            <Star className="w-4 h-4 fill-brand-primary text-brand-primary" />
                            <span className="text-xs font-bold uppercase tracking-wider text-white">Featured Movie</span>
                        </div>
                    </div>

                    <h1 className="text-5xl md:text-7xl font-black text-white leading-tight uppercase overflow-hidden">
                        {movie.title.split('(')[0].split('').map((char, i) => (
                            <motion.span
                                key={i}
                                initial={{ y: "100%" }}
                                animate={{ y: 0 }}
                                transition={{ duration: 0.5, delay: i * 0.03, ease: [0.33, 1, 0.68, 1] }}
                                className="inline-block"
                            >
                                {char === ' ' ? '\u00A0' : char}
                            </motion.span>
                        ))}
                        <br />
                        <motion.span
                            initial={{ opacity: 0, x: -20 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ delay: 0.8 }}
                            className="text-brand-primary"
                        >
                            {movie.genres[0] || 'CINEMATIC'}
                        </motion.span>
                    </h1>

                    <p className="text-lg text-white/70 leading-relaxed font-medium">
                        Experience the next generation of storytelling with {movie.title}. Join a community of millions and discover exclusive content.
                    </p>

                    <div className="flex items-center gap-6 pt-4">
                        <Magnetic>
                            <button className="flex items-center gap-2 bg-brand-primary hover:bg-brand-secondary text-white px-8 py-5 rounded-2xl font-black text-lg shadow-[0_15px_30px_-10px_rgba(229,9,20,0.4)] transition-all active:scale-95">
                                <Play className="w-6 h-6 fill-white" />
                                <span>Watch Now</span>
                            </button>
                        </Magnetic>

                        <Magnetic>
                            <button className="flex items-center gap-2 bg-white/5 hover:bg-white/10 backdrop-blur-xl text-white px-8 py-5 rounded-2xl font-black text-lg border border-white/10 transition-all">
                                <Info className="w-6 h-6" />
                                <span>More Info</span>
                            </button>
                        </Magnetic>
                    </div>

                    <div className="flex items-center gap-8 pt-6">
                        <div className="text-center">
                            <p className="text-2xl font-black text-white">{movie.rating}</p>
                            <p className="text-xs font-bold text-white/50 uppercase tracking-widest">Rating</p>
                        </div>
                        <div className="w-px h-10 bg-white/10" />
                        <div className="text-center">
                            <p className="text-2xl font-black text-white">{movie.year}</p>
                            <p className="text-xs font-bold text-white/50 uppercase tracking-widest">Release</p>
                        </div>
                    </div>
                </motion.div>
            </div>

            {/* Floating Badges Visualization */}
            <div className="hidden xl:block absolute right-20 top-1/2 -translate-y-1/2 space-y-4">
                {[1, 2, 3].map((i) => (
                    <motion.div
                        key={i}
                        animate={{ y: [0, -10, 0] }}
                        transition={{ duration: 3, delay: i * 0.5, repeat: Infinity }}
                        className="bg-white/5 backdrop-blur-xl p-4 rounded-3xl border border-white/10 w-48 shadow-2xl"
                    >
                        <div className="h-24 bg-white/10 rounded-2xl mb-3 animate-pulse" />
                        <div className="h-4 bg-white/20 rounded-full w-3/4 mb-2" />
                        <div className="h-3 bg-white/10 rounded-full w-1/2" />
                    </motion.div>
                ))}
            </div>
        </section>
    );
};

export default Hero;
