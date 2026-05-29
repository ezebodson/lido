import { useEffect, useState } from 'react';
import { 
  CalendarDaysIcon, 
  UserGroupIcon,
  CurrencyDollarIcon,
  ChartBarIcon,
  ArrowTrendingUpIcon,
  ClockIcon,
} from '@heroicons/react/24/outline';
import { dashboardService } from '../services/dashboardService';
import { reservationService } from '../services/reservationService';
import type { DashboardStats, Reservation } from '../types';

const StatCard = ({ 
  title, 
  value, 
  icon: Icon, 
  trend,
  color = 'primary' 
}: { 
  title: string; 
  value: string | number; 
  icon: any;
  trend?: string;
  color?: string;
}) => (
  <div className="card">
    <div className="flex items-center justify-between">
      <div>
        <p className="text-sm text-gray-600 mb-1">{title}</p>
        <p className="text-3xl font-bold text-gray-900">{value}</p>
        {trend && (
          <p className="text-sm text-green-600 flex items-center gap-1 mt-2">
            <ArrowTrendingUpIcon className="w-4 h-4" />
            {trend}
          </p>
        )}
      </div>
      <div className={`p-4 bg-${color}-100 rounded-lg`}>
        <Icon className={`w-8 h-8 text-${color}-600`} />
      </div>
    </div>
  </div>
);

export const DashboardPage = () => {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [todayReservations, setTodayReservations] = useState<Reservation[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadData = async () => {
      try {
        const [statsData, reservationsData] = await Promise.all([
          dashboardService.getStats(),
          reservationService.getAll({ 
            date: new Date().toISOString().split('T')[0] 
          }),
        ]);
        
        setStats(statsData);
        setTodayReservations(reservationsData.data);
      } catch (error) {
        console.error('Error al cargar datos:', error);
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-AR', {
      style: 'currency',
      currency: 'ARS',
    }).format(amount);
  };

  const getStatusColor = (status: Reservation['status']) => {
    const colors = {
      confirmed: 'bg-green-100 text-green-800',
      pending: 'bg-yellow-100 text-yellow-800',
      cancelled: 'bg-red-100 text-red-800',
      completed: 'bg-blue-100 text-blue-800',
    };
    return colors[status];
  };

  const getStatusText = (status: Reservation['status']) => {
    const texts = {
      confirmed: 'Confirmada',
      pending: 'Pendiente',
      cancelled: 'Cancelada',
      completed: 'Completada',
    };
    return texts[status];
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p className="text-gray-600 mt-1">
          Resumen general de tu beach club
        </p>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard
          title="Reservaciones Hoy"
          value={stats?.todayReservations || 0}
          icon={CalendarDaysIcon}
          trend="+12% vs. ayer"
          color="primary"
        />
        <StatCard
          title="Próximas Reservaciones"
          value={stats?.upcomingReservations || 0}
          icon={ClockIcon}
          trend="+8% esta semana"
          color="blue"
        />
        <StatCard
          title="Ingresos del Mes"
          value={formatCurrency(stats?.totalRevenue || 0)}
          icon={CurrencyDollarIcon}
          trend="+23% vs. mes anterior"
          color="green"
        />
        <StatCard
          title="Tasa de Ocupación"
          value={`${stats?.occupancyRate || 0}%`}
          icon={ChartBarIcon}
          trend="+5% esta semana"
          color="purple"
        />
      </div>

      {/* Today's Reservations */}
      <div className="card">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-bold text-gray-900">
            Reservaciones de Hoy
          </h2>
          <button className="btn-primary text-sm">
            Ver Todas
          </button>
        </div>

        {todayReservations.length === 0 ? (
          <div className="text-center py-12 text-gray-500">
            <CalendarDaysIcon className="w-12 h-12 mx-auto mb-3 text-gray-400" />
            <p>No hay reservaciones para hoy</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-gray-200">
                  <th className="text-left py-3 px-4 text-sm font-medium text-gray-700">
                    Cliente
                  </th>
                  <th className="text-left py-3 px-4 text-sm font-medium text-gray-700">
                    Horario
                  </th>
                  <th className="text-left py-3 px-4 text-sm font-medium text-gray-700">
                    Amenidad
                  </th>
                  <th className="text-left py-3 px-4 text-sm font-medium text-gray-700">
                    Invitados
                  </th>
                  <th className="text-left py-3 px-4 text-sm font-medium text-gray-700">
                    Estado
                  </th>
                  <th className="text-right py-3 px-4 text-sm font-medium text-gray-700">
                    Monto
                  </th>
                </tr>
              </thead>
              <tbody>
                {todayReservations.map((reservation) => (
                  <tr key={reservation.id} className="border-b border-gray-100 hover:bg-gray-50">
                    <td className="py-3 px-4">
                      <div>
                        <p className="font-medium text-gray-900">{reservation.customerName}</p>
                        <p className="text-sm text-gray-500">{reservation.customerEmail}</p>
                      </div>
                    </td>
                    <td className="py-3 px-4 text-gray-700">
                      {reservation.startTime} - {reservation.endTime}
                    </td>
                    <td className="py-3 px-4 text-gray-700">
                      {reservation.amenityName || '-'}
                    </td>
                    <td className="py-3 px-4">
                      <div className="flex items-center gap-1 text-gray-700">
                        <UserGroupIcon className="w-4 h-4" />
                        {reservation.numberOfGuests}
                      </div>
                    </td>
                    <td className="py-3 px-4">
                      <span className={`px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(reservation.status)}`}>
                        {getStatusText(reservation.status)}
                      </span>
                    </td>
                    <td className="py-3 px-4 text-right font-medium text-gray-900">
                      {reservation.totalAmount ? formatCurrency(reservation.totalAmount) : '-'}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Quick Actions */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="card hover:shadow-md transition-shadow cursor-pointer">
          <CalendarDaysIcon className="w-10 h-10 text-primary-600 mb-3" />
          <h3 className="font-semibold text-gray-900 mb-2">Nueva Reservación</h3>
          <p className="text-sm text-gray-600">
            Crear una nueva reservación para un cliente
          </p>
        </div>

        <div className="card hover:shadow-md transition-shadow cursor-pointer">
          <UserGroupIcon className="w-10 h-10 text-blue-600 mb-3" />
          <h3 className="font-semibold text-gray-900 mb-2">Ver Clientes</h3>
          <p className="text-sm text-gray-600">
            Gestionar base de datos de clientes
          </p>
        </div>

        <div className="card hover:shadow-md transition-shadow cursor-pointer">
          <ChartBarIcon className="w-10 h-10 text-green-600 mb-3" />
          <h3 className="font-semibold text-gray-900 mb-2">Reportes</h3>
          <p className="text-sm text-gray-600">
            Ver estadísticas y análisis detallados
          </p>
        </div>
      </div>
    </div>
  );
};
