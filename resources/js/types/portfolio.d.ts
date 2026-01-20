export interface Project {
    id: number;
    title: string;
    description: string;
    image?: string | null;
    image_url?: string | null;
    link: string | null;
    tags: string[];
    sort_order: number;
    is_archived?: boolean;
    created_at: string;
    updated_at: string;
}

export interface Skill {
    id: number;
    name: string;
    icon?: string | null;
    items: string[];
    sort_order: number;
    created_at: string;
    updated_at: string;
}

export interface Experience {
    id: number;
    role: string;
    company: string;
    period: string;
    description: string;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

export interface Testimonial {
    id: number;
    name: string;
    role: string;
    company: string;
    content: string;
    avatar?: string | null;
    avatar_url?: string | null;
    rating?: number;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

export interface Article {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    featured_image?: string | null;
    featured_image_url?: string | null;
    published_at?: string | null;
    category?: string | null;
    tags: string[];
    views: number;
    reading_time?: number;
    series?: string | null;
    series_order?: number | null;
    is_featured: boolean;
    created_at: string;
    updated_at: string;
}

export interface ContactMessage {
    id: number;
    name: string;
    email: string;
    subject: string;
    message: string;
    read_at?: string | null;
    created_at: string;
    updated_at: string;
}
