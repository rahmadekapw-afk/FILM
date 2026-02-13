import React, { useEffect, useState } from "react";
import { motion, useSpring, useMotionValue } from "framer-motion";

const CustomCursor = () => {
    const cursorX = useMotionValue(-100);
    const cursorY = useMotionValue(-100);

    const springConfig = { damping: 25, stiffness: 150 };
    const springX = useSpring(cursorX, springConfig);
    const springY = useSpring(cursorY, springConfig);

    useEffect(() => {
        const moveCursor = (e) => {
            cursorX.set(e.clientX - 16);
            cursorY.set(e.clientY - 16);
        };

        window.addEventListener("mousemove", moveCursor);
        return () => window.removeEventListener("mousemove", moveCursor);
    }, []);

    return (
        <>
            {/* Spotlight Background */}
            <motion.div
                style={{
                    translateX: springX,
                    translateY: springY,
                }}
                className="pointer-events-none fixed inset-0 z-[9999] h-8 w-8 rounded-full bg-brand-primary/40 blur-[40px]"
            />
            {/* Inner Dot */}
            <motion.div
                style={{
                    translateX: springX,
                    translateY: springY,
                }}
                className="pointer-events-none fixed left-3.5 top-3.5 z-[9999] h-1.5 w-1.5 rounded-full bg-white/80"
            />
        </>
    );
};

export default CustomCursor;
