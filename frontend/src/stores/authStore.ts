import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import type { User, BeachClub } from '../types';

interface AuthStore {
  user: User | null;
  token: string | null;
  beachClub: BeachClub | null;
  isAuthenticated: boolean;
  
  setAuth: (user: User, token: string) => void;
  setBeachClub: (beachClub: BeachClub) => void;
  logout: () => void;
}

export const useAuthStore = create<AuthStore>()(
  persist(
    (set) => ({
      user: null,
      token: null,
      beachClub: null,
      isAuthenticated: false,

      setAuth: (user, token) => {
        localStorage.setItem('auth_token', token);
        set({ user, token, isAuthenticated: true });
      },

      setBeachClub: (beachClub) => {
        set({ beachClub });
      },

      logout: () => {
        localStorage.removeItem('auth_token');
        set({ user: null, token: null, isAuthenticated: false, beachClub: null });
      },
    }),
    {
      name: 'auth-storage',
    }
  )
);
