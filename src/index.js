//import React from 'react';
//import ReactDOM from 'react-dom/client';
import { HashRouter as Router, Route, Routes, Link } from 'react-router-dom';
import Dashboard from './components/Dashboard';
import Settings from './components/Settings';

const App = () => (
  <Router>
    <div>
      {/* Navigation Links */}
      <nav>
        <ul>
          <li>
            <Link to="/">Dashboard</Link>
          </li>
          <li>
            <Link to="/settings">Settings</Link>
          </li>
        </ul>
      </nav>

      {/* Routes for different views */}
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/settings" element={<Settings />} />
      </Routes>
    </div>
  </Router>
);

// Create a root element and render the app
const rootElement = document.getElementById('my-react-app');
if (rootElement) {
  const root = ReactDOM.createRoot(rootElement);
  root.render(<App />);
} else {
  console.error('No root element found for React!');
}
