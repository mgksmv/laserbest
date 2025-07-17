import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';

// https://primevue.org/theming/styled/#noir
const customThemePreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{neutral.50}',
            100: '{neutral.100}',
            200: '{neutral.200}',
            300: '{neutral.300}',
            400: '{neutral.400}',
            500: '{neutral.500}',
            600: '{neutral.600}',
            700: '{neutral.700}',
            800: '{neutral.800}',
            900: '{neutral.900}',
            950: '{neutral.950}',
        },
        colorScheme: {
            light: {
                primary: {
                    color: '{neutral.950}',
                    inverseColor: '#ffffff',
                    hoverColor: '{neutral.900}',
                    activeColor: '{neutral.800}',
                },
                highlight: {
                    background: '{neutral.950}',
                    focusBackground: '{neutral.700}',
                    color: '#ffffff',
                    focusColor: '#ffffff',
                },
            },
            dark: {
                primary: {
                    color: '{neutral.50}',
                    inverseColor: '{neutral.950}',
                    hoverColor: '{neutral.100}',
                    activeColor: '{neutral.200}',
                },
                highlight: {
                    background: 'rgba(250, 250, 250, .16)',
                    focusBackground: 'rgba(250, 250, 250, .24)',
                    color: 'rgba(255,255,255,.87)',
                    focusColor: 'rgba(255,255,255,.87)',
                },
            },
        },
    },
});

export default customThemePreset;
