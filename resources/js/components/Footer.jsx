import React from 'react';
import { Play, Github, Twitter, Instagram, Mail } from 'lucide-react';

const Footer = () => {
    return (
        <footer className="bg-bg-card border-t border-white/5 pt-20 pb-10 mt-20">
            <div className="container mx-auto px-4 md:px-6">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div className="space-y-6">
                        <a href="/" className="flex items-center gap-2">
                            <div className="bg-brand-primary p-1 rounded-md">
                                <Play className="w-5 h-5 fill-white text-white" />
                            </div>
                            <span className="text-xl font-black tracking-tighter text-white">MOVIX</span>
                        </a>
                        <p className="text-white/50 text-sm leading-relaxed max-w-xs">
                            The ultimate destination for movie lovers. Stream your favorite movies and TV shows in 4K Ultra HD anywhere, anytime.
                        </p>
                        <div className="flex items-center gap-4">
                            {[Twitter, Instagram, Github, Mail].map((Icon, i) => (
                                <a key={i} href="#" className="p-2.5 bg-white/5 rounded-xl text-white/50 hover:text-brand-primary hover:bg-white/10 transition-all">
                                    <Icon className="w-5 h-5" />
                                </a>
                            ))}
                        </div>
                    </div>

                    <div>
                        <h4 className="text-white font-bold mb-6">Explore</h4>
                        <ul className="space-y-4">
                            {['Action Movies', 'Comedy Movies', 'Horror Movies', 'Animation', 'TV Series'].map((link) => (
                                <li key={link}>
                                    <a href="#" className="text-white/50 hover:text-white text-sm transition-colors">{link}</a>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div>
                        <h4 className="text-white font-bold mb-6">Support</h4>
                        <ul className="space-y-4">
                            {['FAQ', 'Help Center', 'Terms of Use', 'Privacy Policy', 'Cookie Preferences'].map((link) => (
                                <li key={link}>
                                    <a href="#" className="text-white/50 hover:text-white text-sm transition-colors">{link}</a>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="space-y-6">
                        <h4 className="text-white font-bold">Newsletter</h4>
                        <p className="text-white/50 text-sm">Subscribe to get the latest updates and movie news.</p>
                        <div className="relative">
                            <input
                                type="email"
                                placeholder="Your email address"
                                className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-6 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 text-white"
                            />
                            <button className="absolute right-2 top-1/2 -translate-y-1/2 bg-brand-primary p-2 rounded-xl hover:bg-brand-secondary transition-colors">
                                <Play className="w-4 h-4 fill-white text-white rotate-0" />
                            </button>
                        </div>
                    </div>
                </div>

                <div className="border-t border-white/5 pt-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p className="text-white/30 text-xs font-medium">
                        © 2024 MOVIX Entertainment. All rights reserved.
                    </p>
                    <div className="flex items-center gap-8">
                        <a href="#" className="text-white/30 hover:text-white text-xs transition-colors">Terms of Service</a>
                        <a href="#" className="text-white/30 hover:text-white text-xs transition-colors">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    );
};

export default Footer;
