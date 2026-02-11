import React, { useState } from 'react';
import { User, Lock, ArrowRight, Eye, EyeOff, Loader2 } from 'lucide-react';
import { useNavigate, Link } from 'react-router-dom';
import Waves from '../components/ReactBits/Waves';
import DecryptedText from '../components/ReactBits/DecryptedText';
import SpotlightCard from '../components/ReactBits/SpotlightCard';

const LoginPage = () => {
    const [userId, setUserId] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const navigate = useNavigate();

    const handleLogin = (e) => {
        e.preventDefault();
        setIsLoading(true);

        // Simulating login for demo purposes
        setTimeout(() => {
            const userData = {
                id: userId,
                name: userId || 'Guest User', // Using ID as name for demo
                avatar: null
            };
            localStorage.setItem('user', JSON.stringify(userData));

            setIsLoading(false);
            navigate('/home');
        }, 1500);
    };


    return (
        <div className="min-h-screen bg-bg-dark text-white flex items-center justify-center p-6 relative overflow-hidden">
            {/* Background Animation */}
            <Waves
                lineColor="rgba(229, 9, 20, 0.3)"
                waveSpeed={0.5}
                waveOpacity={0.5}
            />

            <div className="relative z-10 w-full max-w-[420px] animate-in fade-in zoom-in duration-700">
                <SpotlightCard
                    className="bg-neutral-900/40 backdrop-blur-3xl border border-neutral-800 rounded-[2.5rem] p-10 md:p-12 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.8)]"
                    spotlightColor="rgba(229, 9, 20, 0.15)"
                >
                    <div className="text-center mb-10">
                        {/* Avatar / Icon Section */}
                        <div className="w-24 h-24 bg-gradient-to-tr from-brand-primary to-brand-secondary rounded-full mx-auto mb-8 flex items-center justify-center shadow-[0_0_30px_rgba(229,9,20,0.4)] relative">
                            <div className="absolute inset-0 rounded-full animate-ping bg-brand-primary/20" />
                            <User className="w-12 h-12 text-white" />
                        </div>

                        <DecryptedText
                            text="LOGIN"
                            animateOn="load"
                            revealDirection="center"
                            className="text-3xl font-black tracking-[0.2em] mb-3"
                            speed={50}
                        />
                        <p className="text-neutral-500 text-sm font-medium tracking-wide">
                            Masuk untuk melihat rekomendasi film Anda
                        </p>
                    </div>

                    <form onSubmit={handleLogin} className="space-y-6">
                        {/* User ID Field */}
                        <div className="space-y-2 group">
                            <label className="text-[10px] text-neutral-500 font-black uppercase tracking-[0.2em] ml-2 group-focus-within:text-brand-primary transition-colors">
                                User ID
                            </label>
                            <div className="relative">
                                <div className="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-600 group-focus-within:text-brand-primary transition-colors">
                                    <User className="w-5 h-5" />
                                </div>
                                <input
                                    type="text"
                                    value={userId}
                                    onChange={(e) => setUserId(e.target.value)}
                                    placeholder="Masukkan ID Anda"
                                    className="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl pl-14 pr-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-700 font-medium"
                                    required
                                />
                            </div>
                        </div>

                        {/* Password Field */}
                        <div className="space-y-2 group">
                            <label className="text-[10px] text-neutral-500 font-black uppercase tracking-[0.2em] ml-2 group-focus-within:text-brand-primary transition-colors">
                                Password (opsional)
                            </label>
                            <div className="relative">
                                <div className="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-600 group-focus-within:text-brand-primary transition-colors">
                                    <Lock className="w-5 h-5" />
                                </div>
                                <input
                                    type={showPassword ? "text" : "password"}
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                    placeholder="••••••••"
                                    className="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl pl-14 pr-14 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-700 font-medium"
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute right-5 top-1/2 -translate-y-1/2 text-neutral-600 hover:text-white transition-colors"
                                >
                                    {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                                </button>
                            </div>
                        </div>

                        {/* Login Button */}
                        <button
                            type="submit"
                            disabled={isLoading}
                            className="w-full bg-brand-primary hover:bg-brand-secondary disabled:bg-brand-primary/40 disabled:cursor-not-allowed text-white font-black py-6 rounded-2xl transition-all flex items-center justify-center gap-4 group shadow-2xl shadow-brand-primary/20 active:scale-[0.98] relative overflow-hidden mt-8"
                        >
                            <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer" />
                            {isLoading ? (
                                <Loader2 className="w-6 h-6 animate-spin" />
                            ) : (
                                <>
                                    <span className="uppercase tracking-[0.3em] ml-2">LOGIN</span>
                                    <ArrowRight className="w-5 h-5 group-hover:translate-x-2 transition-transform duration-300" />
                                </>
                            )}
                        </button>
                    </form>

                    <div className="mt-12 text-center space-y-4">
                        <p className="text-neutral-600 text-[10px] font-bold uppercase tracking-widest leading-relaxed">
                            Belum punya ID? Gunakan salah satu User ID<br />
                            dari dataset <span className="text-neutral-400">MovieLens</span>.
                        </p>

                        <div className="pt-4 border-t border-neutral-800">
                            <Link to="/" className="text-neutral-500 hover:text-brand-primary text-xs font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                                <ArrowRight className="w-3 h-3 rotate-180" />
                                Back to Home
                            </Link>
                        </div>
                    </div>
                </SpotlightCard>

                {/* Status Indicator */}
                <div className="mt-12 flex justify-center">
                    <div className="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-neutral-900/50 backdrop-blur-md border border-neutral-800 text-neutral-600 text-[9px] font-black uppercase tracking-[0.25em] shadow-2xl">
                        <div className="w-1.5 h-1.5 rounded-full bg-brand-primary animate-pulse shadow-[0_0_8px_rgba(229,9,20,0.6)]" />
                        Secure Session Active
                    </div>
                </div>
            </div>
        </div>
    );
};

export default LoginPage;
