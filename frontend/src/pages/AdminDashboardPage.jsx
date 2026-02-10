import { useState } from 'react';
import ProductManagement from '../components/admin/ProductManagement';
import OrderManagement from '../components/admin/OrderManagement';

const AdminDashboardPage = () => {
  const [activeTab, setActiveTab] = useState('products');

  return (
    <div className="admin-dashboard">
      <h1>Tableau de bord Admin</h1>
      <div className="admin-tabs">
        <button
          className={activeTab === 'products' ? 'active' : ''}
          onClick={() => setActiveTab('products')}
        >
          Produits
        </button>
        <button
          className={activeTab === 'orders' ? 'active' : ''}
          onClick={() => setActiveTab('orders')}
        >
          Commandes
        </button>
      </div>
      <div className="admin-content">
        {activeTab === 'products' && <ProductManagement />}
        {activeTab === 'orders' && <OrderManagement />}
      </div>
    </div>
  );
};

export default AdminDashboardPage;
