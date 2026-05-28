import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { apiClient } from '../api/client'
import { useAuthStore } from '../store/authStore'

function LoginPage() {
  const navigate = useNavigate()
  const setAuth = useAuthStore((state) => state.setAuth)
  const [form, setForm] = useState({
    email: 'admin@demo.test',
    password: 'password',
  })
  const [error, setError] = useState('')

  const onSubmit = async (event) => {
    event.preventDefault()
    setError('')

    try {
      const { data } = await apiClient.post('/auth/login', form)
      setAuth(data)
      navigate('/dashboard', { replace: true })
    } catch (requestError) {
      setError(requestError.response?.data?.message || 'Login failed')
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-100 p-4">
      <form
        className="w-full max-w-sm space-y-3 rounded-lg border bg-white p-6"
        onSubmit={onSubmit}
      >
        <h1 className="text-xl font-semibold">Sign in</h1>
        <input
          className="w-full rounded border px-3 py-2"
          placeholder="Email"
          value={form.email}
          onChange={(event) =>
            setForm((old) => ({ ...old, email: event.target.value }))
          }
        />
        <input
          type="password"
          className="w-full rounded border px-3 py-2"
          placeholder="Password"
          value={form.password}
          onChange={(event) =>
            setForm((old) => ({ ...old, password: event.target.value }))
          }
        />
        {error ? <p className="text-sm text-rose-600">{error}</p> : null}
        <button
          className="w-full rounded bg-slate-900 px-3 py-2 text-white"
          type="submit"
        >
          Login
        </button>
      </form>
    </div>
  )
}

export default LoginPage
