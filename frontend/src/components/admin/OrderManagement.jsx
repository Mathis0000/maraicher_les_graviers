import { useState, useEffect } from 'react';
import * as orderService from '../../services/orderService';

const OrderManagement = () => {
  const [orders, setOrders] = useState([]);

  useEffect(() => {
    fetchOrders();
  }, []);

  const fetchOrders = async () => {
    try {
      const data = await orderService.getOrders();
      setOrders(data);
    } catch (error) {
      console.error('Error fetching orders:', error);
    }
  };

  const handleStatusChange = async (orderId, newStatus) => {
    try {
      await orderService.updateOrderStatus(orderId, newStatus);
      fetchOrders();
      alert('Statut mis à jour');
    } catch (error) {
      alert('Erreur lors de la mise à jour');
    }
  };

  const getStatusLabel = (status) => {
    const labels = {
      pending: 'En attente',
      confirmed: 'Confirmée',
      delivered: 'Livrée',
      cancelled: 'Annulée'
    };
    return labels[status] || status;
  };

  return (
    <div className="order-management">
      <h2>Gestion des commandes</h2>
      <div className="orders-table">
        {orders.map((order) => (
          <div key={order.id} className="order-admin-card">
            <div className="order-admin-header">
              <h3>Commande #{order.id.slice(0, 8)}</h3>
              <select
                value={order.status}
                onChange={(e) => handleStatusChange(order.id, e.target.value)}
                className="status-select"
              >
                <option value="pending">En attente</option>
                <option value="confirmed">Confirmée</option>
                <option value="delivered">Livrée</option>
                <option value="cancelled">Annulée</option>
              </select>
            </div>
            <div className="order-admin-details">
              <p><strong>Client:</strong> {order.first_name} {order.last_name} ({order.email})</p>
              <p><strong>Date:</strong> {new Date(order.created_at).toLocaleDateString('fr-FR')}</p>
              <p><strong>Montant:</strong> {parseFloat(order.total_amount).toFixed(2)} €</p>
              <p><strong>Livraison:</strong> {order.delivery_address}, {order.delivery_city} {order.delivery_postal_code}</p>
              <p><strong>Téléphone:</strong> {order.delivery_phone}</p>
              {order.notes && <p><strong>Notes:</strong> {order.notes}</p>}
            </div>
            {order.items && (
              <div className="order-admin-items">
                <h4>Articles:</h4>
                {(Array.isArray(order.items) ? order.items : JSON.parse(order.items || '[]')).map((item, index) => (
                  <div key={index} className="order-admin-item">
                    <span>{item.product_name} x {item.quantity} {item.unit}</span>
                    <span>{parseFloat(item.subtotal).toFixed(2)} €</span>
                  </div>
                ))}
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
};

export default OrderManagement;
