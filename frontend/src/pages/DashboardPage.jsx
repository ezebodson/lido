import { useEffect, useState } from 'react'
import { apiClient } from '../api/client'
import MetricCard from '../components/MetricCard'

function DashboardPage() {
  const [metrics, setMetrics] = useState(null)

  useEffect(() => {
    apiClient
      .get('/dashboard/metrics')
      .then((response) => setMetrics(response.data.data))
  }, [])

  if (!metrics) {
    return <p>Loading metrics...</p>
  }

  return (
    <div className="grid gap-4 md:grid-cols-5">
      <MetricCard label="Units" value={metrics.units} />
      <MetricCard label="Customers" value={metrics.customers} />
      <MetricCard
        label="Active Reservations"
        value={metrics.active_reservations}
      />
      <MetricCard label="Occupancy %" value={metrics.occupancy_rate} />
      <MetricCard label="Today Payments" value={`€${metrics.today_payments}`} />
    </div>
  )
}

export default DashboardPage
