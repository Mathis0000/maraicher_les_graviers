import { useState, useEffect } from 'react';
import * as orderService from '../../services/orderService';

const OrderHistory = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const data = await orderService.getOrders();
        setOrders(data);
      } catch (error) {
        console.error('Error fetching orders:', error);
      } finally {
        setLoading(false);
      }
    };
    fetchOrders();
  }, []);

  const getStatusLabel = (status) => {
    const labels = {
      pending: 'En attente',
      confirmed: 'Confirmée',
      delivered: 'Livrée',
      cancelled: 'Annulée'
    };
    return labels[status] || status;
  };

  if (loading) return <div>Chargement...</div>;

  if (orders.length === 0) {
    return <div className="no-orders"><p>Aucune commande</p></div>;
  }

  return (
    <div className="order-history">
      <h2>Mes commandes</h2>
      {orders.map((order) => (
        <div key={order.id} className="order-card">
          <div className="order-header">
            <span className="order-id">Commande #{order.id.slice(0, 8)}</span>
            <span className={`order-status status-${order.status}`}>
              {getStatusLabel(order.status)}
            </span>
          </div>
          <div className="order-details">
            <p>Date: {new Date(order.created_at).toLocaleDateString('fr-FR')}</p>
            <p>Montant: {parseFloat(order.total_amount).toFixed(2)} €</p>
            <p>Livraison: {order.delivery_address}, {order.delivery_city}</p>
          </div>
          {order.items && (
            <div className="order-items">
              <h4>Articles:</h4>
              {(Array.isArray(order.items) ? order.items : JSON.parse(order.items || '[]')).map((item, index) => (
                <div key={index} className="order-item">
                  <span>{item.product_name} x {item.quantity}</span>
                  <span>{parseFloat(item.subtotal).toFixed(2)} €</span>
                </div>
              ))}
            </div>
          )}
        </div>
      ))}
    </div>
  );
};

export default OrderHistory;
