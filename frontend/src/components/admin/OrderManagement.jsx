import { useState, useEffect } from 'react';
import * as orderService from '../../services/orderService';

const OrderManagement = () => {
  const [orders, setOrders] = useState([]);
  const [statusFilter, setStatusFilter] = useState('all');
  const [sortByClient, setSortByClient] = useState(false);

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

  const filteredOrders = orders.filter((order) => {
    if (statusFilter === 'all') {
      return true;
    }
    return order.status === statusFilter;
  });

  const sortedOrders = [...filteredOrders].sort((a, b) => {
    if (sortByClient) {
      const aName = `${a.last_name || ''} ${a.first_name || ''}`.trim().toLowerCase();
      const bName = `${b.last_name || ''} ${b.first_name || ''}`.trim().toLowerCase();
      return aName.localeCompare(bName, 'fr');
    }
    return new Date(b.created_at) - new Date(a.created_at);
  });

  return (
    <div className="order-management">
      <h2>Gestion des commandes</h2>
      <div className="orders-controls">
        <span>Trier par:</span>
        <select
          value={statusFilter}
          onChange={(e) => setStatusFilter(e.target.value)}
          className="btn btn-secondary status-select"
        >
          <option value="all">Statut: Tous</option>
          <option value="pending">Statut: En attente</option>
          <option value="confirmed">Statut: Confirmée</option>
          <option value="delivered">Statut: Livrée</option>
          <option value="cancelled">Statut: Annulée</option>
        </select>
        <button
          type="button"
          className={`btn btn-secondary ${sortByClient ? 'active' : ''}`}
          onClick={() => setSortByClient((prev) => !prev)}
        >
          Client (A-Z)
        </button>
      </div>
      <div className="orders-table">
        {sortedOrders.map((order) => (
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
