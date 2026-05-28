import { NavLink, Outlet, useNavigate } from 'react-router-dom'
import { apiClient } from '../api/client'
import { useAuthStore } from '../store/authStore'

const links = [
  { to: '/dashboard', label: 'Dashboard' },
  { to: '/reservations', label: 'Reservations' },
  { to: '/map', label: 'Beach Map' },
  { to: '/customers', label: 'Customers' },
  { to: '/payments', label: 'Payments' },
  { to: '/rates', label: 'Rates' },
]

function Layout() {
  const navigate = useNavigate()
  const user = useAuthStore((state) => state.user)
  const logout = useAuthStore((state) => state.logout)

  const handleLogout = async () => {
    try {
      await apiClient.post('/auth/logout')
    } catch {
      // ignore logout API failures
    } finally {
      logout()
      navigate('/login', { replace: true })
    }
  }

  return (
    <div className="min-h-screen">
      <header className="border-b bg-white">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
          <div>
            <h1 className="text-lg font-semibold">Lido MVP</h1>
            <p className="text-xs text-slate-500">
              {user?.name} · {user?.role}
            </p>
          </div>
          <button
            className="rounded bg-slate-900 px-3 py-2 text-sm text-white"
            onClick={handleLogout}
          >
            Logout
          </button>
        </div>
        <nav className="mx-auto flex max-w-7xl gap-2 px-4 pb-3">
          {links.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) =>
                `rounded px-3 py-1.5 text-sm ${isActive ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-700'}`
              }
            >
              {link.label}
            </NavLink>
          ))}
        </nav>
      </header>
      <main className="mx-auto max-w-7xl p-4">
        <Outlet />
      </main>
    </div>
  )
}

export default Layout
