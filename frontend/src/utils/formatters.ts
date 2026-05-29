export const formatCurrency = (amount: number, currency: string = 'ARS'): string => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency,
  }).format(amount);
};

export const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString('es-AR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  });
};

export const formatTime = (timeString: string): string => {
  return timeString;
};

export const formatDateTime = (dateTimeString: string): string => {
  return new Date(dateTimeString).toLocaleString('es-AR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

export const formatPhoneNumber = (phone: string): string => {
  // Básico: solo devuelve el teléfono tal cual
  // Se puede mejorar con formateo específico
  return phone;
};
