import { useEffect, useState } from 'react'
import { apiClient } from '../api/client'

function RatesPage() {
  const [rates, setRates] = useState([])

  useEffect(() => {
    apiClient.get('/rates').then((response) => setRates(response.data.data))
  }, [])

  return (
    <div className="overflow-x-auto rounded-lg border bg-white">
      <table className="min-w-full text-sm">
        <thead className="bg-slate-50 text-left">
          <tr>
            <th className="p-2">Name</th>
            <th className="p-2">Type</th>
            <th className="p-2">Daily Price</th>
            <th className="p-2">Date Range</th>
          </tr>
        </thead>
        <tbody>
          {rates.map((rate) => (
            <tr key={rate.id} className="border-t">
              <td className="p-2">{rate.name}</td>
              <td className="p-2">{rate.unit_type}</td>
              <td className="p-2">€{rate.daily_price}</td>
              <td className="p-2">
                {rate.start_date} → {rate.end_date}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default RatesPage
