import { useEffect, useState } from 'react'
import { apiClient } from '../api/client'

function CustomersPage() {
  const [customers, setCustomers] = useState([])

  useEffect(() => {
    apiClient
      .get('/customers')
      .then((response) => setCustomers(response.data.data))
  }, [])

  return (
    <div className="overflow-x-auto rounded-lg border bg-white">
      <table className="min-w-full text-sm">
        <thead className="bg-slate-50 text-left">
          <tr>
            <th className="p-2">Name</th>
            <th className="p-2">Email</th>
            <th className="p-2">Phone</th>
          </tr>
        </thead>
        <tbody>
          {customers.map((customer) => (
            <tr key={customer.id} className="border-t">
              <td className="p-2">
                {customer.first_name} {customer.last_name}
              </td>
              <td className="p-2">{customer.email}</td>
              <td className="p-2">{customer.phone}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default CustomersPage
