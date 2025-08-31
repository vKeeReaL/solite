export interface Product {
  id: string;
  name: string;
  description: string;
  price: string;
  image: string;
  category: string;
  burnTime: string;
  tags: string[];
  inStock: boolean;
  rating: number;
  reviews: number;
}

export const products: Product[] = [
  {
    id: "1",
    name: "Bespoke Handmade Violet Candle",
    description: "Exquisite handcrafted candle with a beautiful violet gypsum base and palm wax infused with Lavender Musk fragrance. Perfect for creating a relaxing atmosphere.",
    price: "AU $11.99",
    image: "/products/violet-candle.jpg",
    category: "Luxury",
    burnTime: "8 hours",
    tags: ["handmade", "violet", "gypsum", "palm wax", "lavender musk", "relaxation"],
    inStock: true,
    rating: 5.0,
    reviews: 12
  },
  {
    id: "2",
    name: "Bespoke Handmade Blue Candle",
    description: "Stunning handcrafted candle featuring a blue gypsum base and palm wax with Lavender Musk scent. Ideal for meditation and stress relief.",
    price: "AU $11.99",
    image: "/products/blue-candle.jpg",
    category: "Luxury",
    burnTime: "8 hours",
    tags: ["handmade", "blue", "gypsum", "palm wax", "lavender musk", "meditation"],
    inStock: true,
    rating: 5.0,
    reviews: 8
  },
  {
    id: "3",
    name: "Bespoke Handmade White Candle - English Pear & Freesia",
    description: "Elegant white candle with gypsum base featuring the delightful fragrance of English Pear & Freesia. Perfect for romantic evenings and special occasions.",
    price: "AU $11.99",
    image: "/products/white-candle-1.jpg",
    category: "Romance",
    burnTime: "8 hours",
    tags: ["handmade", "white", "gypsum", "english pear", "freesia", "romance"],
    inStock: true,
    rating: 5.0,
    reviews: 15
  },
  {
    id: "4",
    name: "Bespoke Handmade White Candle - English Pear & Freesia",
    description: "Classic white candle with gypsum base and the enchanting scent of English Pear & Freesia. Creates an atmosphere of elegance and sophistication.",
    price: "AU $11.99",
    image: "/products/white-candle-2.jpg",
    category: "Romance",
    burnTime: "8 hours",
    tags: ["handmade", "white", "gypsum", "english pear", "freesia", "elegance"],
    inStock: true,
    rating: 5.0,
    reviews: 10
  }
];

export const categories = [
  "All",
  "Luxury",
  "Romance",
  "Relaxation",
  "Meditation"
];

export function getProductsByCategory(category: string): Product[] {
  if (category === "All") return products;
  return products.filter(product => product.category === category);
}

export function getFeaturedProducts(): Product[] {
  return products.filter(product => product.rating >= 4.5);
}

export function getProductById(id: string): Product | undefined {
  return products.find(product => product.id === id);
}
