import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { Camera, Film, PlusCircle, AlertCircle, CheckCircle2, ArrowLeft, Trash2, Edit3, Save, X, Loader2, FileSpreadsheet, Upload } from 'lucide-react';
import { Link } from 'react-router-dom';
import SpotlightCard from '../components/ReactBits/SpotlightCard';

const AdminPage = () => {
    const [movies, setMovies] = useState([]);
    const [title, setTitle] = useState('');
    const [genres, setGenres] = useState('');
    const [poster, setPoster] = useState(null);
    const [preview, setPreview] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const [isFetching, setIsFetching] = useState(true);
    const [isImporting, setIsImporting] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [importMessage, setImportMessage] = useState({ type: '', text: '' });
    const [editingId, setEditingId] = useState(null);
    const fileInputRef = useRef(null);

    useEffect(() => {
        fetchMovies();
    }, []);

    const fetchMovies = async () => {
        setIsFetching(true);
        try {
            const response = await axios.get('/api/movies');
            setMovies(response.data);
        } catch (error) {
            console.error('Error fetching movies:', error);
        } finally {
            setIsFetching(false);
        }
    };

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setPoster(file);
            setPreview(URL.createObjectURL(file));
        }
    };

    const resetForm = () => {
        setTitle('');
        setGenres('');
        setPoster(null);
        setPreview(null);
        setEditingId(null);
        setMessage({ type: '', text: '' });
    };

    const handleEdit = (movie) => {
        setEditingId(movie.id);
        setTitle(movie.title);
        setGenres(movie.genres.join('|'));
        setPreview(movie.image);
        setPoster(null);
        setMessage({ type: '', text: '' });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleDelete = async (id) => {
        if (!confirm('Are you sure you want to delete this movie?')) return;

        try {
            await axios.delete(`/api/movies/${id}`);
            setMessage({ type: 'success', text: 'Movie deleted successfully!' });
            fetchMovies();
        } catch (error) {
            console.error('Error deleting movie:', error);
            setMessage({ type: 'error', text: 'Failed to delete movie.' });
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        setMessage({ type: '', text: '' });

        const formData = new FormData();
        formData.append('title', title);
        formData.append('genres', genres);
        if (poster) {
            formData.append('poster', poster);
        }

        try {
            const url = editingId ? `/api/movies/${editingId}` : '/api/movies';
            const response = await axios.post(url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            setMessage({ type: 'success', text: response.data.message });
            resetForm();
            fetchMovies();
        } catch (error) {
            console.error('Error saving movie:', error);
            setMessage({
                type: 'error',
                text: error.response?.data?.message || 'Failed to save movie. Please check your input.'
            });
        } finally {
            setIsLoading(false);
        }
    };

    const handleImportClick = () => {
        fileInputRef.current?.click();
    };

    const handleFileImport = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        setIsImporting(true);
        setImportMessage({ type: '', text: '' });

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await axios.post('/api/movies/import', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            setImportMessage({ type: 'success', text: response.data.message });
            fetchMovies();
            // Reset input
            e.target.value = '';
        } catch (error) {
            console.error('Error importing movies:', error);
            setImportMessage({
                type: 'error',
                text: error.response?.data?.message || 'Failed to import Excel. Ensure format is correct.'
            });
            e.target.value = '';
        } finally {
            setIsImporting(false);
        }
    };

    return (
        <div className="min-h-screen bg-bg-dark text-white selection:bg-brand-primary/30 overflow-x-hidden pb-32">


            <div className="relative z-10 max-w-5xl mx-auto px-6 py-12">
                <Link
                    to="/"
                    className="inline-flex items-center gap-2 text-neutral-400 hover:text-white transition-colors mb-8 group"
                >
                    <ArrowLeft className="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                    Back to Movies
                </Link>

                <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-2">
                    <div className="flex items-center gap-4">
                        <div className="p-3 bg-brand-primary/20 rounded-2xl border border-brand-primary/30">
                            <Film className="w-8 h-8 text-brand-primary" />
                        </div>
                        <div>
                            <h1 className="text-4xl font-bold tracking-tight block">
                                {editingId ? "Edit Movie" : "Add New Movie"}
                            </h1>
                            <p className="text-neutral-400 mt-1">
                                {editingId ? `Updating: ${title}` : "Populate your movie library with new titles"}
                            </p>
                        </div>
                    </div>
                    <div className="flex items-center gap-3">
                        {editingId && (
                            <button
                                onClick={resetForm}
                                className="flex items-center gap-2 px-6 py-3 bg-neutral-800 hover:bg-neutral-700 rounded-2xl transition-all border border-neutral-700 text-sm font-bold uppercase tracking-widest text-neutral-300"
                            >
                                <X className="w-4 h-4" />
                                Cancel Edit
                            </button>
                        )}

                        <input
                            type="file"
                            ref={fileInputRef}
                            onChange={handleFileImport}
                            accept=".xlsx,.xls,.csv"
                            className="hidden"
                        />
                        <button
                            onClick={handleImportClick}
                            disabled={isImporting}
                            className={`flex items-center gap-2 px-6 py-3 bg-emerald-600/10 hover:bg-emerald-600/20 rounded-2xl transition-all border border-emerald-600/30 text-sm font-bold uppercase tracking-widest text-emerald-400 ${isImporting ? 'opacity-50 cursor-not-allowed' : ''}`}
                        >
                            {isImporting ? <Loader2 className="w-4 h-4 animate-spin" /> : <FileSpreadsheet className="w-4 h-4" />}
                            {isImporting ? 'Importing...' : 'Import Excel'}
                        </button>
                    </div>
                </div>

                {importMessage.text && (
                    <div className={`mt-6 flex items-center gap-4 p-5 rounded-[2rem] border ${importMessage.type === 'success'
                        ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400'
                        : 'bg-red-500/10 border-red-500/30 text-red-400'
                        }`}>
                        {importMessage.type === 'success' ? <CheckCircle2 className="w-6 h-6 flex-shrink-0" /> : <AlertCircle className="w-6 h-6 flex-shrink-0" />}
                        <span className="text-sm font-black uppercase tracking-widest">{importMessage.text}</span>
                        <button onClick={() => setImportMessage({ type: '', text: '' })} className="ml-auto opacity-50 hover:opacity-100">
                            <X className="w-4 h-4" />
                        </button>
                    </div>
                )}

                <div className="mt-12">
                    <SpotlightCard
                        className="bg-neutral-900/40 backdrop-blur-3xl border border-neutral-800 rounded-[2.5rem] p-8 md:p-12 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.5)]"
                        spotlightColor="rgba(229, 9, 20, 0.1)"
                    >
                        <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-12">
                            {/* Left Column: Details */}
                            <div className="space-y-8">
                                <div className="group">
                                    <label className="block text-sm font-medium text-neutral-400 mb-3 group-focus-within:text-brand-primary transition-colors uppercase tracking-widest">
                                        Movie Title
                                    </label>
                                    <input
                                        type="text"
                                        value={title}
                                        onChange={(e) => setTitle(e.target.value)}
                                        placeholder="e.g. Inception (2010)"
                                        className="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl px-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-600 backdrop-blur-sm text-lg"
                                        required
                                    />
                                </div>

                                <div className="group">
                                    <label className="block text-sm font-medium text-neutral-400 mb-3 group-focus-within:text-brand-primary transition-colors uppercase tracking-widest">
                                        Genres
                                    </label>
                                    <input
                                        type="text"
                                        value={genres}
                                        onChange={(e) => setGenres(e.target.value)}
                                        placeholder="e.g. Action|Sci-Fi|Thriller"
                                        className="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl px-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-600 backdrop-blur-sm text-lg"
                                        required
                                    />
                                    <p className="text-[10px] uppercase tracking-widest text-neutral-500 mt-3 font-black px-1 opacity-70">
                                        Use pipe <span className="text-brand-primary px-1">|</span> to separate multiple genres
                                    </p>
                                </div>

                                {message.text && (
                                    <div className={`flex items-center gap-4 p-6 rounded-2xl border ${message.type === 'success'
                                        ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 font-bold'
                                        : 'bg-red-500/10 border-red-500/30 text-red-400 font-bold'
                                        }`}>
                                        {message.type === 'success' ? <CheckCircle2 className="w-6 h-6 flex-shrink-0" /> : <AlertCircle className="w-6 h-6 flex-shrink-0" />}
                                        <span className="text-sm">{message.text}</span>
                                    </div>
                                )}

                                <button
                                    type="submit"
                                    disabled={isLoading}
                                    className="w-full bg-brand-primary hover:bg-brand-secondary disabled:bg-brand-primary/40 disabled:cursor-not-allowed text-white font-black py-6 rounded-2xl transition-all flex items-center justify-center gap-4 group shadow-2xl shadow-brand-primary/20 active:scale-[0.98] relative overflow-hidden text-lg"
                                >
                                    <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full" />
                                    {isLoading ? (
                                        <Loader2 className="w-6 h-6 animate-spin text-white" />
                                    ) : (
                                        <>
                                            {editingId ? <Save className="w-6 h-6" /> : <PlusCircle className="w-6 h-6 group-hover:rotate-90 transition-transform duration-500" />}
                                            <span className="uppercase tracking-[0.2em]">{editingId ? 'Update Movie' : 'Add Movie'}</span>
                                        </>
                                    )}
                                </button>
                            </div>

                            {/* Right Column: Poster Upload */}
                            <div className="space-y-6">
                                <label className="block text-sm font-medium text-neutral-400 mb-3 uppercase tracking-widest text-center md:text-left">
                                    Movie Poster
                                </label>
                                <div
                                    className={`relative aspect-[2/3] w-full max-w-[320px] mx-auto md:mx-0 rounded-[2rem] border-2 border-dashed transition-all duration-500 flex flex-col items-center justify-center overflow-hidden group shadow-inner ${preview ? 'border-brand-primary/50' : 'border-neutral-800 hover:border-brand-primary/50 bg-neutral-950/50'
                                        }`}
                                >
                                    {preview ? (
                                        <>
                                            <img
                                                src={preview}
                                                alt="Preview"
                                                className="w-full h-full object-cover transition-transform group-hover:scale-110 duration-1000"
                                            />
                                            <div className="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center gap-4 backdrop-blur-md">
                                                <div className="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center border border-white/20 shadow-2xl">
                                                    <Camera className="w-8 h-8 text-white" />
                                                </div>
                                                <span className="text-xs font-black uppercase tracking-[0.2em] text-white">Change Poster</span>
                                            </div>
                                        </>
                                    ) : (
                                        <div className="text-center p-10">
                                            <div className="w-24 h-24 bg-neutral-900 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 border border-neutral-800 group-hover:scale-110 group-hover:bg-brand-primary/10 group-hover:border-brand-primary/30 transition-all duration-700">
                                                <Camera className="w-12 h-12 text-neutral-500 group-hover:text-brand-primary transition-colors duration-500" />
                                            </div>
                                            <p className="text-neutral-400 font-black text-xl mb-2 uppercase tracking-widest">Upload</p>
                                            <p className="text-neutral-600 text-xs font-bold tracking-widest uppercase opacity-70">600x900 recommended</p>
                                        </div>
                                    )}
                                    <input
                                        type="file"
                                        onChange={handleImageChange}
                                        accept="image/*"
                                        className="absolute inset-0 opacity-0 cursor-pointer"
                                    />
                                </div>
                            </div>
                        </form>
                    </SpotlightCard>
                </div>

                {/* Movie List Section */}
                <div className="mt-32">
                    <div className="flex items-center justify-between mb-12">
                        <h2 className="text-3xl font-black flex items-center gap-4 uppercase tracking-[0.1em]">
                            <div className="w-1.5 h-10 bg-brand-primary rounded-full shadow-[0_0_15px_rgba(229,9,20,0.5)]" />
                            Dashboard
                        </h2>
                        <div className="px-4 py-2 rounded-xl bg-neutral-900/50 border border-neutral-800 text-neutral-500 text-xs font-black uppercase tracking-widest">
                            Total: <span className="text-white ml-1">{movies.length}</span>
                        </div>
                    </div>

                    {isFetching ? (
                        <div className="flex flex-col items-center justify-center py-24 gap-6 grayscale opacity-50">
                            <Loader2 className="w-12 h-12 animate-spin text-brand-primary" />
                            <p className="text-sm font-black uppercase tracking-widest text-neutral-500">Scanning Database...</p>
                        </div>
                    ) : movies.length === 0 ? (
                        <div className="text-center py-32 rounded-[3rem] border-2 border-dashed border-neutral-800 bg-neutral-900/10">
                            <Film className="w-16 h-16 mx-auto mb-6 text-neutral-800" />
                            <p className="text-neutral-500 uppercase font-black tracking-widest">Your library is empty</p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {movies.map((movie) => (
                                <div
                                    key={movie.id}
                                    className="group relative bg-neutral-900/20 backdrop-blur-sm border border-neutral-800/50 rounded-[2rem] p-5 flex gap-6 hover:bg-neutral-900/40 transition-all duration-500 hover:border-brand-primary/30 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.4)]"
                                >
                                    <div className="w-24 h-36 rounded-2xl overflow-hidden flex-shrink-0 bg-neutral-800 border border-neutral-700/50 shadow-lg transition-transform duration-500 group-hover:scale-[1.02]">
                                        {movie.image ? (
                                            <img src={movie.image} alt={movie.title} className="w-full h-full object-cover" />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center italic text-[10px] text-neutral-600 text-center px-1 font-bold">MISSING_POSTER</div>
                                        )}
                                    </div>
                                    <div className="flex-1 min-w-0 flex flex-col justify-center pr-12">
                                        <h3 className="font-black text-xl truncate mb-2 group-hover:text-brand-primary transition-colors duration-500 uppercase tracking-tight">{movie.title}</h3>
                                        <div className="flex flex-wrap gap-2 mb-4">
                                            {movie.genres.map((g, i) => (
                                                <span key={i} className="text-[10px] font-black uppercase tracking-widest text-neutral-500 bg-neutral-800/50 px-2 py-1 rounded-md border border-neutral-700/30">{g}</span>
                                            ))}
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <span className="px-3 py-1 bg-neutral-950/50 rounded-lg text-[10px] text-neutral-500 border border-neutral-800 font-black">#{movie.id}</span>
                                            <span className="px-3 py-1 bg-brand-primary/5 rounded-lg text-[10px] text-brand-primary border border-brand-primary/20 font-black tracking-tighter">★ {movie.rating || '0.0'}</span>
                                        </div>
                                    </div>
                                    <div className="absolute top-5 right-5 flex flex-col gap-3">
                                        <button
                                            onClick={() => handleEdit(movie)}
                                            className="p-3 bg-neutral-950/80 hover:bg-brand-primary rounded-2xl transition-all duration-300 border border-neutral-800 hover:border-brand-primary group/btn shadow-xl active:scale-95"
                                            title="Edit Title"
                                        >
                                            <Edit3 className="w-5 h-5 text-neutral-400 group-hover/btn:text-white transition-colors" />
                                        </button>
                                        <button
                                            onClick={() => handleDelete(movie.id)}
                                            className="p-3 bg-neutral-950/80 hover:bg-red-600 rounded-2xl transition-all duration-300 border border-neutral-800 hover:border-red-600 group/btn shadow-xl active:scale-95"
                                            title="Destroy Entry"
                                        >
                                            <Trash2 className="w-5 h-5 text-neutral-400 group-hover/btn:text-white transition-colors" />
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                <div className="mt-24 text-center">
                    <div className="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-neutral-900 border border-neutral-800 text-neutral-500 text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl">
                        <div className="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]" />
                        Administrator Level Access • v2.0 CRUD
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AdminPage;
