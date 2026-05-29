import apiClient from '../api/client';
import type { 
  LoginCredentials, 
  AuthResponse, 
  User 
} from '../types';

// Datos mock para desarrollo
const MOCK_USER: User = {
  id: '1',
  email: 'admin@lido.com',
  firstName: 'Admin',
  lastName: 'LIDO',
  role: 'admin',
};

const MOCK_TOKEN = 'mock-jwt-token-12345';

export const authService = {
  // Login con API o mock
  login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
    try {
      const response = await apiClient.post<AuthResponse>('/auth/login', credentials);
      return response.data;
    } catch (error) {
      // Fallback a datos mock si la API no está disponible
      console.warn('API no disponible, usando datos mock');
      
      // Simular validación
      if (credentials.email === 'admin@lido.com' && credentials.password === 'admin123') {
        return {
          user: MOCK_USER,
          token: MOCK_TOKEN,
        };
      }
      
      throw new Error('Credenciales inválidas');
    }
  },

  // Logout
  logout: async (): Promise<void> => {
    try {
      await apiClient.post('/auth/logout');
    } catch (error) {
      console.warn('Error al hacer logout en API:', error);
    }
  },

  // Obtener usuario actual
  getCurrentUser: async (): Promise<User> => {
    try {
      const response = await apiClient.get<User>('/auth/me');
      return response.data;
    } catch (error) {
      console.warn('API no disponible, usando datos mock');
      return MOCK_USER;
    }
  },
};
