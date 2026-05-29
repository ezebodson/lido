import apiClient from '../api/client';
import type { 
  Reservation, 
  CreateReservationData,
  PaginatedResponse 
} from '../types';

// Datos mock para desarrollo
const MOCK_RESERVATIONS: Reservation[] = [
  {
    id: '1',
    customerName: 'Juan Pérez',
    customerEmail: 'juan@example.com',
    customerPhone: '+54 11 1234-5678',
    date: '2024-06-15',
    startTime: '10:00',
    endTime: '14:00',
    numberOfGuests: 4,
    status: 'confirmed',
    amenityName: 'Carpa Premium #5',
    totalAmount: 25000,
    notes: 'Celebración de cumpleaños',
    createdAt: '2024-05-20T10:00:00Z',
    updatedAt: '2024-05-20T10:00:00Z',
  },
  {
    id: '2',
    customerName: 'María González',
    customerEmail: 'maria@example.com',
    customerPhone: '+54 11 9876-5432',
    date: '2024-06-15',
    startTime: '15:00',
    endTime: '19:00',
    numberOfGuests: 2,
    status: 'pending',
    amenityName: 'Carpa Standard #12',
    totalAmount: 15000,
    createdAt: '2024-05-21T14:30:00Z',
    updatedAt: '2024-05-21T14:30:00Z',
  },
  {
    id: '3',
    customerName: 'Carlos Rodríguez',
    customerEmail: 'carlos@example.com',
    customerPhone: '+54 11 5555-4444',
    date: '2024-06-16',
    startTime: '11:00',
    endTime: '16:00',
    numberOfGuests: 6,
    status: 'confirmed',
    amenityName: 'Carpa VIP #2',
    totalAmount: 35000,
    notes: 'Evento corporativo',
    createdAt: '2024-05-22T09:15:00Z',
    updatedAt: '2024-05-22T09:15:00Z',
  },
];

export const reservationService = {
  // Obtener todas las reservaciones
  getAll: async (params?: {
    page?: number;
    pageSize?: number;
    status?: string;
    date?: string;
  }): Promise<PaginatedResponse<Reservation>> => {
    try {
      const response = await apiClient.get<PaginatedResponse<Reservation>>('/reservations', {
        params,
      });
      return response.data;
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      
      // Filtrar datos mock según parámetros
      let filtered = [...MOCK_RESERVATIONS];
      
      if (params?.status) {
        filtered = filtered.filter(r => r.status === params.status);
      }
      
      if (params?.date) {
        filtered = filtered.filter(r => r.date === params.date);
      }
      
      return {
        data: filtered,
        total: filtered.length,
        page: params?.page || 1,
        pageSize: params?.pageSize || 10,
        totalPages: Math.ceil(filtered.length / (params?.pageSize || 10)),
      };
    }
  },

  // Obtener una reservación por ID
  getById: async (id: string): Promise<Reservation> => {
    try {
      const response = await apiClient.get<Reservation>(`/reservations/${id}`);
      return response.data;
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      const reservation = MOCK_RESERVATIONS.find(r => r.id === id);
      if (!reservation) {
        throw new Error('Reservación no encontrada');
      }
      return reservation;
    }
  },

  // Crear nueva reservación
  create: async (data: CreateReservationData): Promise<Reservation> => {
    try {
      const response = await apiClient.post<Reservation>('/reservations', data);
      return response.data;
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      
      const newReservation: Reservation = {
        id: String(MOCK_RESERVATIONS.length + 1),
        ...data,
        status: 'pending',
        totalAmount: 20000,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
      };
      
      MOCK_RESERVATIONS.push(newReservation);
      return newReservation;
    }
  },

  // Actualizar estado de reservación
  updateStatus: async (id: string, status: Reservation['status']): Promise<Reservation> => {
    try {
      const response = await apiClient.patch<Reservation>(`/reservations/${id}/status`, {
        status,
      });
      return response.data;
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      const reservation = MOCK_RESERVATIONS.find(r => r.id === id);
      if (!reservation) {
        throw new Error('Reservación no encontrada');
      }
      reservation.status = status;
      reservation.updatedAt = new Date().toISOString();
      return reservation;
    }
  },

  // Eliminar reservación
  delete: async (id: string): Promise<void> => {
    try {
      await apiClient.delete(`/reservations/${id}`);
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      const index = MOCK_RESERVATIONS.findIndex(r => r.id === id);
      if (index !== -1) {
        MOCK_RESERVATIONS.splice(index, 1);
      }
    }
  },
};
