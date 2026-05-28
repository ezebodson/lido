import { useEffect, useState } from 'react'
import { apiClient } from '../api/client'

function ReservationsPage() {
  const [filters, setFilters] = useState({ status: '', from: '', to: '' })
  const [reservations, setReservations] = useState([])
  const [occupancy, setOccupancy] = useState([])

  const applyFilters = async () => {
    const params = Object.fromEntries(
      Object.entries(filters).filter(([, value]) => value)
    )
    const [reservationResponse, occupancyResponse] = await Promise.all([
      apiClient.get('/reservations', { params }),
      apiClient.get('/calendar/occupancy', {
        params: { start_date: filters.from, end_date: filters.to },
      }),
    ])
    setReservations(reservationResponse.data.data)
    setOccupancy(occupancyResponse.data.data.days.slice(0, 7))
  }

  useEffect(() => {
    apiClient
      .get('/reservations')
      .then((response) => setReservations(response.data.data))
    apiClient
      .get('/calendar/occupancy')
      .then((response) => setOccupancy(response.data.data.days.slice(0, 7)))
  }, [])

  return (
    <div className="space-y-4">
      <div className="grid gap-2 rounded-lg border bg-white p-3 md:grid-cols-4">
        <input
          className="rounded border px-2 py-1"
          placeholder="Status"
          value={filters.status}
          onChange={(event) =>
            setFilters((old) => ({ ...old, status: event.target.value }))
          }
        />
        <input
          type="date"
          className="rounded border px-2 py-1"
          value={filters.from}
          onChange={(event) =>
            setFilters((old) => ({ ...old, from: event.target.value }))
          }
        />
        <input
          type="date"
          className="rounded border px-2 py-1"
          value={filters.to}
          onChange={(event) =>
            setFilters((old) => ({ ...old, to: event.target.value }))
          }
        />
        <button
          className="rounded bg-slate-900 px-3 py-1 text-white"
          onClick={applyFilters}
        >
          Apply
        </button>
      </div>

      <div className="overflow-x-auto rounded-lg border bg-white">
        <table className="min-w-full text-sm">
          <thead className="bg-slate-50 text-left">
            <tr>
              <th className="p-2">Unit</th>
              <th className="p-2">Customer</th>
              <th className="p-2">Dates</th>
              <th className="p-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {reservations.map((reservation) => (
              <tr key={reservation.id} className="border-t">
                <td className="p-2">{reservation.unit?.code}</td>
                <td className="p-2">{reservation.customer?.name}</td>
                <td className="p-2">
                  {reservation.start_date} → {reservation.end_date}
                </td>
                <td className="p-2">{reservation.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <section className="rounded-lg border bg-white p-4">
        <h2 className="mb-2 text-lg font-semibold">Calendar placeholder</h2>
        <div className="grid gap-2 md:grid-cols-7">
          {occupancy.map((day) => (
            <div key={day.date} className="rounded border p-2 text-xs">
              <p className="font-medium">{day.date}</p>
              <p>
                {day.occupied_units}/{day.total_units} occupied
              </p>
            </div>
          ))}
        </div>
      </section>
    </div>
  )
}

export default ReservationsPage
