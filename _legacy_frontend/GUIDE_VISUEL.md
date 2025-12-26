# 🎨 Guide de Style - Central Auto B

## 🎯 Vue d'ensemble des améliorations

### Navigation
- ✅ Logo avec animation shine
- ✅ Menu avec underline animé
- ✅ Navbar qui se réduit au scroll
- ✅ Bouton CTA avec effet glow

### Hero Section
- ✅ Logo central avec animation
- ✅ Background avec parallax
- ✅ Blobs flottants décoratifs
- ✅ Badge premium glassmorphism
- ✅ Titre avec gradient animé
- ✅ Deux CTA : principal (gradient) + secondaire (glass)

### Services (3 cartes)
- ✅ Bordure dégradée au survol
- ✅ Icônes avec rotation 6° au hover
- ✅ Background gradient subtil
- ✅ Flèche "En savoir plus" qui slide
- ✅ Ombres profondes au survol

### Stock de véhicules
- ✅ Onglets avec effet shimmer
- ✅ Cards avec bordure gradient invisible → visible
- ✅ Image avec scale 1.1x au hover
- ✅ Overlay noir fade-in
- ✅ Icône cœur qui apparaît en haut à droite
- ✅ Prix en couleur (bleu pour achat, rouge pour location)
- ✅ Icônes specs colorées (orange/bleu/rouge)
- ✅ Bouton avec gradient et slide

### Formulaire personnalisé
- ✅ 3 blobs flottants en background
- ✅ Formulaire glassmorphism
- ✅ Radio cards avec border animé
- ✅ Inputs avec transition au focus
- ✅ Bouton submit avec glow effect
- ✅ Badge sécurité en bas

### Footer
- ✅ Background sombre (slate-900/800)
- ✅ Logo en version inversée (blanc)
- ✅ Icônes sociales carrées avec border
- ✅ Séparateurs avec gradient
- ✅ Hover effects sur tous les liens

### CTA "Pas trouvé"
- ✅ Card avec bordure gradient en haut
- ✅ Icône magnifying glass qui pulse
- ✅ Bouton gradient complet

## 🎨 Effets Spéciaux

### Animations CSS
```
1. logoShine - 3s infinite (brillance logo)
2. shimmer - 2s infinite (lumière qui passe)
3. gradientShift - 3s infinite (déplacement gradient)
4. float - 3s infinite (flottement Y)
5. pulse-slow - 2s infinite (opacité)
6. fadeIn - 0.6s (apparition)
```

### Hover Effects
- Transform: translateY(-8px) scale(1.02)
- Box-shadow: progressive
- Border-gradient: opacity 0 → 0.6
- Color transitions
- Icon rotations

### Transitions
- Duration: 300-500ms
- Easing: cubic-bezier(0.4, 0, 0.2, 1)
- Properties: all, transform, opacity, colors

## 📐 Espacements

- Sections: py-20 à py-28
- Cards: p-7 à p-12
- Gaps: 8 à 12 (2rem à 3rem)
- Rounded: 2xl (1rem) à 3xl (1.5rem)

## 🎯 Points d'attention

1. **Le logo doit être présent** dans `unnamed.png`
2. **Toutes les animations** sont GPU-accelerated
3. **Mobile-first** - tout est responsive
4. **Accessibilité** - focus states présents
5. **Performance** - will-change sur animations critiques

## 🚀 Pour tester

Ouvrez simplement `index.html` dans un navigateur moderne (Chrome, Firefox, Safari, Edge)

---

**Tous les effets sont visibles au survol et au scroll !**
