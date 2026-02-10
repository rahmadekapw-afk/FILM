import React, { useEffect, useState, useRef } from 'react';

const DecryptedText = ({
    text,
    speed = 50,
    maxIterations = 10,
    sequential = false,
    revealDirection = 'start',
    useHover = false,
    className = "",
    parentClassName = "",
    animateOn = 'view'
}) => {
    const [displayText, setDisplayText] = useState(text);
    const [isHovering, setIsHovering] = useState(false);
    const [isRevealing, setIsRevealing] = useState(false);
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
    const containerRef = useRef(null);

    useEffect(() => {
        if (animateOn === 'view') {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !isRevealing) {
                    triggerAnimate();
                }
            }, { threshold: 0.1 });
            if (containerRef.current) observer.observe(containerRef.current);
            return () => observer.disconnect();
        } else if (animateOn === 'load') {
            triggerAnimate();
        }
    }, []);

    const triggerAnimate = () => {
        setIsRevealing(true);
        let iteration = 0;
        const interval = setInterval(() => {
            setDisplayText(prevText =>
                text.split('').map((char, index) => {
                    if (char === ' ') return ' ';
                    if (index < iteration / maxIterations * text.length) return text[index];
                    return characters[Math.floor(Math.random() * characters.length)];
                }).join('')
            );

            if (iteration >= maxIterations * text.length) {
                clearInterval(interval);
                setIsRevealing(false);
            }
            iteration++;
        }, speed);
    };

    return (
        <span
            ref={containerRef}
            className={parentClassName}
            onMouseEnter={() => useHover && triggerAnimate()}
        >
            <span className={className}>{displayText}</span>
        </span>
    );
};

export default DecryptedText;
