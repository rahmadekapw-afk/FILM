import React, { useState, useEffect } from 'react';
import { Search, Play, User, Menu, X } from 'lucide-react';
import { Link } from 'react-router-dom';

const Navbar = ({ onSearch }) => {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const handleSearchChange = (e) => {
        const value = e.target.value;
        setSearchQuery(value);
        if (onSearch) {
            onSearch(value);
        }
    };

    return (
        <nav className={`fixed w-full z-50 transition-all duration-300 ${isScrolled ? 'bg-bg-dark/95 backdrop-blur-md border-b border-white/10 py-3' : 'bg-transparent py-5'}`}>
            <div className="container mx-auto px-4 md:px-6 flex items-center justify-between">
                <div className="flex items-center gap-8">
                    <Link to="/" className="flex items-center gap-2 group">
                        <div className="bg-brand-primary p-1 rounded-md group-hover:bg-brand-secondary transition-colors">
                            <Play className="w-6 h-6 fill-white text-white" />
                        </div>
                        <span className="text-2xl font-black tracking-tighter text-white">MOVIX</span>
                    </Link>

                    <div className="hidden md:flex items-center gap-6">
                        {['Movies', 'TV Shows', 'New & Popular', 'My List'].map((item) => (
                            <a key={item} href={`#${item}`} className="text-sm font-medium text-white/70 hover:text-white transition-colors">{item}</a>
                        ))}
                    </div>
                </div>

                <div className="flex items-center gap-4">
                    <div className="relative hidden lg:block">
                        <input
                            type="text"
                            value={searchQuery}
                            onChange={handleSearchChange}
                            placeholder="Search titles..."
                            className="bg-white/5 border border-white/10 rounded-full py-1.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 w-64 transition-all"
                        />
                        <Search className="w-4 h-4 text-white/50 absolute left-4 top-1/2 -translate-y-1/2" />
                    </div>

                    <button className="p-2 text-white/70 hover:text-white lg:hidden">
                        <Search className="w-5 h-5" />
                    </button>

                    <Link to="/login" className="hidden sm:flex items-center gap-2 bg-brand-primary hover:bg-brand-secondary text-white px-4 py-1.5 rounded-full text-sm font-semibold transition-all">
                        <User className="w-4 h-4" />
                        <span>Sign In</span>
                    </Link>

                    <button onClick={() => setIsMenuOpen(!isMenuOpen)} className="p-2 text-white md:hidden">
                        {isMenuOpen ? <X /> : <Menu />}
                    </button>
                </div>
            </div>

            {/* Mobile Menu */}
            {isMenuOpen && (
                <div className="md:hidden bg-bg-dark border-b border-white/10 px-4 py-6 space-y-4 animate-in fade-in slide-in-from-top-4">
                    {['Movies', 'TV Shows', 'New & Popular', 'My List'].map((item) => (
                        <a key={item} href={`#${item}`} className="block text-lg font-medium text-white/70 hover:text-white">{item}</a>
                    ))}
                    <Link to="/login" className="block text-center w-full bg-brand-primary hover:bg-brand-secondary text-white py-3 rounded-xl font-bold transition-all">
                        Sign In
                    </Link>
                </div>
            )}
        </nav>
    );
};

export default Navbar;
