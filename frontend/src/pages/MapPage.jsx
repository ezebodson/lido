import { useEffect, useState } from 'react'
import { apiClient } from '../api/client'

function MapPage() {
  const [groups, setGroups] = useState({})

  useEffect(() => {
    apiClient
      .get('/units/map')
      .then((response) => setGroups(response.data.data))
  }, [])

  return (
    <div className="space-y-4">
      {Object.entries(groups).map(([sector, units]) => (
        <section key={sector} className="rounded-lg border bg-white p-4">
          <h2 className="mb-3 text-lg font-semibold">{sector}</h2>
          <div className="grid grid-cols-2 gap-2 md:grid-cols-6">
            {units.map((unit) => (
              <div key={unit.id} className="rounded border p-2 text-xs">
                <p className="font-medium">{unit.code}</p>
                <p className="capitalize">{unit.status}</p>
              </div>
            ))}
          </div>
        </section>
      ))}
    </div>
  )
}

export default MapPage
