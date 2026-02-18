# Filament Admin Panel - Neon Glass Dashboard Theme

## Theme Overview
**Aesthetic Direction**: Cyberpunk/DevOps command center with glassmorphism effects

**Key Design Elements**:
- Dark base with neon accent colors (cyan, magenta, purple, green)
- Grid pattern background for technical feel
- Glass morphism with backdrop blur and saturation
- Glow effects on interactive elements
- Space Grotesk + JetBrains Mono font combination

## Color Palette

### Primary Colors
- `--fi-primary`: #00d4ff (Neon Cyan)
- `--fi-secondary`: #8b5cf6 (Purple)
- `--neon-cyan`: #06b6d4
- `--neon-magenta`: #f472b6
- `--neon-purple`: #a78bfa
- `--neon-green`: #10b981
- `--neon-orange`: #f97316

### Background Colors
- `--bg-primary`: #0a0a0f
- `--bg-secondary`: #12121a
- `--bg-tertiary`: #1a1a24
- `--bg-card`: rgba(26, 26, 36, 0.8)

### Text Colors
- `--text-primary`: #f1f5f9
- `--text-secondary`: #94a3b8
- `--text-muted`: #64748b

### Effects
- `--glow-cyan`: 0 0 20px rgba(6, 182, 212, 0.4)
- `--glow-magenta`: 0 0 20px rgba(244, 114, 182, 0.4)
- `--glow-purple`: 0 0 20px rgba(167, 139, 250, 0.4)

## Typography
- **Display**: Space Grotesk (300, 400, 500, 600, 700)
- **Monospace**: JetBrains Mono (400, 500, 600, 700)

## Components Customized

### Navigation Sidebar
- Glass effect with backdrop blur
- Hover states with gradient overlay
- Active state with cyan glow
- Icon glow effects

### Cards
- Glass morphism with backdrop blur
- Hover border glow
- Elevated shadow on hover

### Tables
- Transparent background
- Glass container
- Monospace headers with uppercase
- Hover row effects

### Buttons
- Gradient background (cyan to purple)
- Shimmer effect on hover
- Glow shadow
- Smooth transitions

### Stats Cards
- Gradient top border
- Animated gradient values
- Float animation on hover

### Inputs
- Dark glass background
- Cyan glow on focus
- Smooth transitions

## Animations

### Keyframes Defined
- `neonPulse`: Pulsing glow effect
- `gradientShift`: Moving gradient background
- `float`: Vertical floating motion

## Files Modified
1. `resources/css/filament/admin/theme.css` - Complete theme override
2. `app/Providers/Filament/AdminPanelProvider.php` - Color scheme update

## Usage Notes
- Theme automatically applies to all Filament resources
- Custom CSS variables can be adjusted for color variants
- Glass effects require browser backdrop-filter support
