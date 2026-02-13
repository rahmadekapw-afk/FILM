import React, { useState } from 'react';
import { User, Lock, Mail, ArrowRight, Eye, EyeOff, Loader2 } from 'lucide-react';
import { useNavigate, Link } from 'react-router-dom';
import SpotlightCard from '../components/ReactBits/SpotlightCard';
import axios from 'axios';

const RegisterPage = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const navigate = useNavigate();

    const handleRegister = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');
        setSuccess('');

        try {
            const response = await axios.post('/api/register', {
                email,
                password
            });

            if (response.data.user) {
                setSuccess('Pendaftaran berhasil! Mengalihkan ke halaman login...');
                setTimeout(() => {
                    navigate('/login');
                }, 2000);
            }
        } catch (err) {
            console.error('Registration error:', err);
            setError(err.response?.data?.message || 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-bg-dark text-white flex items-center justify-center p-6 relative overflow-hidden">
            <div className="relative z-10 w-full max-w-[420px]">
                <SpotlightCard
                    className="bg-neutral-900/40 backdrop-blur-3xl border border-neutral-800 rounded-[2.5rem] p-10 md:p-12 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.8)]"
                    spotlightColor="rgba(229, 9, 20, 0.15)"
                >
                    <div className="text-center mb-10">
                        <div className="w-24 h-24 bg-gradient-to-tr from-brand-primary to-brand-secondary rounded-full mx-auto mb-8 flex items-center justify-center shadow-[0_0_30px_rgba(229,9,20,0.4)] relative">
                            <User className="w-12 h-12 text-white" />
                        </div>

                        <h2 className="text-3xl font-black tracking-[0.2em] mb-3 text-white">
                            SIGN UP
                        </h2>
                        <p className="text-neutral-500 text-sm font-medium tracking-wide">
                            Buat akun baru untuk menikmati fitur lengkap
                        </p>
                    </div>

                    {error && (
                        <div className="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-xs font-medium">
                            {error}
                        </div>
                    )}

                    {success && (
                        <div className="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-xs font-medium">
                            {success}
                        </div>
                    )}

                    <form onSubmit={handleRegister} className="space-y-6">


                        {/* Email Field */}
                        <div className="space-y-2 group">
                            <label className="text-[10px] text-neutral-500 font-black uppercase tracking-[0.2em] ml-2 group-focus-within:text-brand-primary transition-colors">
                                Email Address
                            </label>
                            <div className="relative">
                                <div className="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-600 group-focus-within:text-brand-primary transition-colors">
                                    <Mail className="w-5 h-5" />
                                </div>
                                <input
                                    type="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="nama@email.com"
                                    className="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl pl-14 pr-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-700 font-medium"
                                    required
                                />
                            </div>
                        </div>

                        {/* Password Field */}
                        <div className="space-y-2 group">
                            <label className="text-[10px] text-neutral-500 font-black uppercase tracking-[0.2em] ml-2 group-focus-within:text-brand-primary transition-colors">
                                Password
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
                                    required
                                    minLength={4}
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

                        {/* Register Button */}
                        <button
                            type="submit"
                            disabled={isLoading}
                            className="w-full bg-brand-primary hover:bg-brand-secondary disabled:bg-brand-primary/40 disabled:cursor-not-allowed text-white font-black py-6 rounded-2xl transition-all flex items-center justify-center gap-4 group shadow-2xl shadow-brand-primary/20 active:scale-[0.98] relative overflow-hidden mt-8"
                        >
                            <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full" />
                            {isLoading ? (
                                <Loader2 className="w-6 h-6 animate-spin" />
                            ) : (
                                <>
                                    <span className="uppercase tracking-[0.3em] ml-2">CREATE ACCOUNT</span>
                                    <ArrowRight className="w-5 h-5 group-hover:translate-x-2 transition-transform duration-300" />
                                </>
                            )}
                        </button>
                    </form>

                    <div className="mt-12 text-center space-y-4">
                        <div className="pt-4 border-t border-neutral-800">
                            <p className="text-neutral-500 text-xs font-medium mb-4">
                                Sudah punya akun?
                            </p>
                            <Link to="/login" className="text-white hover:text-brand-primary text-xs font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                                Login Sekarang
                                <ArrowRight className="w-3 h-3" />
                            </Link>
                        </div>
                    </div>
                </SpotlightCard>
            </div>
        </div>
    );
};

export default RegisterPage;
