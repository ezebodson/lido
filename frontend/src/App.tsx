import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ProtectedRoute } from './routes/ProtectedRoute';
import { MainLayout } from './layouts/MainLayout';
import { LoginPage } from './pages/LoginPage';
import { DashboardPage } from './pages/DashboardPage';
import { ReservationsPage } from './pages/ReservationsPage';
import { useAuthStore } from './stores/authStore';
import { useEffect } from 'react';

function App() {
  const { isAuthenticated, setBeachClub } = useAuthStore();

  // Simular carga de datos de beach club al iniciar
  useEffect(() => {
    if (isAuthenticated) {
      // En producción, esto vendría de la API
      setBeachClub({
        id: '1',
        name: 'LIDO Beach Club',
        address: 'Av. Costanera 1234, Mar del Plata',
        phone: '+54 223 123-4567',
        email: 'info@lido.com',
        capacity: 200,
        openingTime: '09:00',
        closingTime: '20:00',
      });
    }
  }, [isAuthenticated, setBeachClub]);

  return (
    <BrowserRouter>
      <Routes>
        {/* Public Routes */}
        <Route
          path="/login"
          element={
            isAuthenticated ? <Navigate to="/dashboard" replace /> : <LoginPage />
          }
        />

        {/* Protected Routes */}
        <Route
          path="/"
          element={
            <ProtectedRoute>
              <MainLayout />
            </ProtectedRoute>
          }
        >
          <Route index element={<Navigate to="/dashboard" replace />} />
          <Route path="dashboard" element={<DashboardPage />} />
          <Route path="reservations" element={<ReservationsPage />} />
          
          {/* Placeholder routes - serán implementadas */}
          <Route
            path="customers"
            element={
              <div className="card">
                <h1 className="text-2xl font-bold mb-4">Clientes</h1>
                <p className="text-gray-600">Módulo en desarrollo...</p>
              </div>
            }
          />
          <Route
            path="reports"
            element={
              <div className="card">
                <h1 className="text-2xl font-bold mb-4">Reportes</h1>
                <p className="text-gray-600">Módulo en desarrollo...</p>
              </div>
            }
          />
          <Route
            path="settings"
            element={
              <div className="card">
                <h1 className="text-2xl font-bold mb-4">Configuración</h1>
                <p className="text-gray-600">Módulo en desarrollo...</p>
              </div>
            }
          />
        </Route>

        {/* Catch all */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
