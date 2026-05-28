import { Navigate, Route, Routes } from 'react-router-dom'
import Layout from './components/Layout'
import ProtectedRoute from './components/ProtectedRoute'
import CustomersPage from './pages/CustomersPage'
import DashboardPage from './pages/DashboardPage'
import LoginPage from './pages/LoginPage'
import MapPage from './pages/MapPage'
import PaymentsPage from './pages/PaymentsPage'
import RatesPage from './pages/RatesPage'
import ReservationsPage from './pages/ReservationsPage'
import { useAuthStore } from './store/authStore'

function App() {
  const token = useAuthStore((state) => state.token)

  return (
    <Routes>
      <Route
        path="/login"
        element={token ? <Navigate to="/dashboard" replace /> : <LoginPage />}
      />
      <Route
        path="/"
        element={
          <ProtectedRoute>
            <Layout />
          </ProtectedRoute>
        }
      >
        <Route index element={<Navigate to="/dashboard" replace />} />
        <Route path="dashboard" element={<DashboardPage />} />
        <Route path="reservations" element={<ReservationsPage />} />
        <Route path="map" element={<MapPage />} />
        <Route path="customers" element={<CustomersPage />} />
        <Route path="payments" element={<PaymentsPage />} />
        <Route path="rates" element={<RatesPage />} />
      </Route>
      <Route
        path="*"
        element={<Navigate to={token ? '/dashboard' : '/login'} replace />}
      />
    </Routes>
  )
}

export default App
