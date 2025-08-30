export interface Product {
  id: number;
  name: string;
  description: string;
  price: string;
  image: string;
  category: string;
  burnTime: string;
  inStock: boolean;
  featured?: boolean;
  tags: string[];
}

export const products: Product[] = [
  {
    id: 1,
    name: "Lavender Dreams",
    description: "A soothing blend of lavender and vanilla that promotes relaxation and peaceful sleep. Perfect for bedtime routines and meditation.",
    price: "$24.99",
    image: "/products/lavender-candle.jpg",
    category: "Relaxation",
    burnTime: "40 hours",
    inStock: true,
    featured: true,
    tags: ["relaxation", "sleep", "meditation", "lavender", "vanilla"]
  },
  {
    id: 2,
    name: "Ocean Breeze",
    description: "Fresh ocean air with hints of sea salt and driftwood for a coastal atmosphere. Brings the beach to your home.",
    price: "$29.99",
    image: "/products/ocean-candle.jpg",
    category: "Fresh",
    burnTime: "50 hours",
    inStock: true,
    featured: true,
    tags: ["fresh", "ocean", "coastal", "sea salt", "driftwood"]
  },
  {
    id: 3,
    name: "Cinnamon Spice",
    description: "Warm and inviting cinnamon with notes of nutmeg and clove for cozy evenings. Perfect for fall and winter months.",
    price: "$26.99",
    image: "/products/cinnamon-candle.jpg",
    category: "Warm",
    burnTime: "45 hours",
    inStock: true,
    featured: false,
    tags: ["warm", "spicy", "cinnamon", "nutmeg", "clove", "fall", "winter"]
  },
  {
    id: 4,
    name: "Rose Garden",
    description: "Romantic rose petals with a touch of jasmine for elegant ambiance. Ideal for date nights and special occasions.",
    price: "$32.99",
    image: "/products/rose-candle.jpg",
    category: "Romantic",
    burnTime: "55 hours",
    inStock: true,
    featured: true,
    tags: ["romantic", "rose", "jasmine", "elegant", "date night"]
  },
  {
    id: 5,
    name: "Pine Forest",
    description: "Crisp pine needles and cedar wood for a natural, outdoorsy feel. Great for creating a cabin or forest atmosphere.",
    price: "$28.99",
    image: "/products/pine-candle.jpg",
    category: "Natural",
    burnTime: "48 hours",
    inStock: true,
    featured: false,
    tags: ["natural", "pine", "cedar", "outdoors", "forest", "cabin"]
  },
  {
    id: 6,
    name: "Vanilla Comfort",
    description: "Classic vanilla with creamy undertones for a warm, familiar atmosphere. A timeless scent that everyone loves.",
    price: "$22.99",
    image: "/products/vanilla-candle.jpg",
    category: "Classic",
    burnTime: "42 hours",
    inStock: true,
    featured: false,
    tags: ["classic", "vanilla", "creamy", "timeless", "comfort"]
  }
];

export const categories = [
  "All",
  "Relaxation",
  "Fresh",
  "Warm",
  "Romantic",
  "Natural",
  "Classic",
  "Energizing",
  "Spiritual"
];

export const getProductsByCategory = (category: string): Product[] => {
  if (category === "All") return products;
  return products.filter(product => product.category === category);
};

export const getFeaturedProducts = (): Product[] => {
  return products.filter(product => product.featured);
};

export const getProductById = (id: number): Product | undefined => {
  return products.find(product => product.id === id);
};
