import apiClient from '../api/client';
import type { DashboardStats } from '../types';

// Datos mock para desarrollo
const MOCK_STATS: DashboardStats = {
  todayReservations: 12,
  upcomingReservations: 45,
  totalRevenue: 450000,
  occupancyRate: 78,
};

export const dashboardService = {
  // Obtener estadísticas del dashboard
  getStats: async (): Promise<DashboardStats> => {
    try {
      const response = await apiClient.get<DashboardStats>('/dashboard/stats');
      return response.data;
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      return MOCK_STATS;
    }
  },
};
