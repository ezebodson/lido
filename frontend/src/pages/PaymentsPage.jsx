import { useEffect, useState } from 'react'
import { apiClient } from '../api/client'

function PaymentsPage() {
  const [payments, setPayments] = useState([])

  useEffect(() => {
    apiClient
      .get('/payments')
      .then((response) => setPayments(response.data.data))
  }, [])

  return (
    <div className="overflow-x-auto rounded-lg border bg-white">
      <table className="min-w-full text-sm">
        <thead className="bg-slate-50 text-left">
          <tr>
            <th className="p-2">Reservation</th>
            <th className="p-2">Amount</th>
            <th className="p-2">Method</th>
            <th className="p-2">Status</th>
          </tr>
        </thead>
        <tbody>
          {payments.map((payment) => (
            <tr key={payment.id} className="border-t">
              <td className="p-2">#{payment.reservation_id}</td>
              <td className="p-2">€{payment.amount}</td>
              <td className="p-2">{payment.method}</td>
              <td className="p-2">{payment.status}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default PaymentsPage
