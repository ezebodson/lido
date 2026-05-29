import { XMarkIcon } from '@heroicons/react/24/outline';
import {
  CheckCircleIcon,
  ExclamationCircleIcon,
  InformationCircleIcon,
  ExclamationTriangleIcon,
} from '@heroicons/react/24/solid';

type AlertType = 'success' | 'error' | 'warning' | 'info';

interface AlertProps {
  type: AlertType;
  title?: string;
  message: string;
  onClose?: () => void;
}

const alertStyles = {
  success: {
    container: 'bg-green-50 border-green-200 text-green-800',
    icon: <CheckCircleIcon className="w-5 h-5 text-green-600" />,
  },
  error: {
    container: 'bg-red-50 border-red-200 text-red-800',
    icon: <ExclamationCircleIcon className="w-5 h-5 text-red-600" />,
  },
  warning: {
    container: 'bg-yellow-50 border-yellow-200 text-yellow-800',
    icon: <ExclamationTriangleIcon className="w-5 h-5 text-yellow-600" />,
  },
  info: {
    container: 'bg-blue-50 border-blue-200 text-blue-800',
    icon: <InformationCircleIcon className="w-5 h-5 text-blue-600" />,
  },
};

export const Alert = ({ type, title, message, onClose }: AlertProps) => {
  const styles = alertStyles[type];

  return (
    <div className={`rounded-lg border p-4 ${styles.container}`}>
      <div className="flex gap-3">
        <div className="flex-shrink-0">{styles.icon}</div>
        <div className="flex-1">
          {title && <h3 className="font-semibold mb-1">{title}</h3>}
          <p className="text-sm">{message}</p>
        </div>
        {onClose && (
          <button
            onClick={onClose}
            className="flex-shrink-0 hover:opacity-70 transition-opacity"
          >
            <XMarkIcon className="w-5 h-5" />
          </button>
        )}
      </div>
    </div>
  );
};
