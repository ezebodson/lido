// Tipos de autenticación
export interface User {
  id: string;
  email: string;
  firstName: string;
  lastName: string;
  role: 'admin' | 'manager' | 'staff';
  avatar?: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}

// Tipos de Beach Club
export interface BeachClub {
  id: string;
  name: string;
  address: string;
  phone: string;
  email: string;
  capacity: number;
  openingTime: string;
  closingTime: string;
  image?: string;
}

// Tipos de Reservaciones
export interface Reservation {
  id: string;
  customerName: string;
  customerEmail: string;
  customerPhone: string;
  date: string;
  startTime: string;
  endTime: string;
  numberOfGuests: number;
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  amenityId?: string;
  amenityName?: string;
  notes?: string;
  totalAmount?: number;
  createdAt: string;
  updatedAt: string;
}

export interface CreateReservationData {
  customerName: string;
  customerEmail: string;
  customerPhone: string;
  date: string;
  startTime: string;
  endTime: string;
  numberOfGuests: number;
  amenityId?: string;
  notes?: string;
}

// Tipos de Amenidades
export interface Amenity {
  id: string;
  name: string;
  description: string;
  capacity: number;
  pricePerHour: number;
  image?: string;
  available: boolean;
}

// Tipos de Dashboard
export interface DashboardStats {
  todayReservations: number;
  upcomingReservations: number;
  totalRevenue: number;
  occupancyRate: number;
}

// Tipos de API
export interface ApiError {
  message: string;
  code?: string;
  details?: any;
}

export interface PaginatedResponse<T> {
  data: T[];
  total: number;
  page: number;
  pageSize: number;
  totalPages: number;
}
