import React, { useRef, useEffect } from 'react';

const Waves = ({
    lineColor = "rgba(129, 140, 248, 0.2)",
    waveSpeed = 0.5,
    waveOpacity = 0.5,
    className = ""
}) => {
    const canvasRef = useRef(null);

    useEffect(() => {
        const canvas = canvasRef.current;
        const ctx = canvas.getContext('2d');
        let animationFrameId;

        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        let offset = 0;

        const draw = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.strokeStyle = lineColor;
            ctx.lineWidth = 1;
            ctx.globalAlpha = waveOpacity;

            const step = 40;
            const lines = 15;

            for (let j = 0; j < lines; j++) {
                ctx.beginPath();
                for (let i = 0; i <= canvas.width; i += 10) {
                    const y = (canvas.height / 2) +
                        Math.sin(i * 0.005 + offset + (j * 0.5)) * 100 +
                        Math.cos(i * 0.002 + offset * 0.5 + (j * 0.2)) * 50;
                    if (i === 0) ctx.moveTo(i, y);
                    else ctx.lineTo(i, y);
                }
                ctx.stroke();
            }

            offset += 0.01 * waveSpeed;
            animationFrameId = requestAnimationFrame(draw);
        };

        draw();

        return () => {
            window.removeEventListener('resize', resizeCanvas);
            cancelAnimationFrame(animationFrameId);
        };
    }, [lineColor, waveSpeed, waveOpacity]);

    return (
        <canvas
            ref={canvasRef}
            className={`fixed inset-0 pointer-events-none z-0 ${className}`}
        />
    );
};

export default Waves;
