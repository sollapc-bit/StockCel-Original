import { Switch, Route } from "wouter";
import { queryClient } from "./lib/queryClient";
import { QueryClientProvider } from "@tanstack/react-query";
import { Toaster } from "@/components/ui/toaster";
import { TooltipProvider } from "@/components/ui/tooltip";
import Landing from "@/pages/landing";
import Login from "@/pages/login";
import AdminDashboard from "@/pages/admin/dashboard";
import AdminRevendedores from "@/pages/admin/revendedores";
import AdminLicencias from "@/pages/admin/licencias";
import AdminClientes from "@/pages/admin/clientes";
import RevendedorDashboard from "@/pages/revendedor/dashboard";
import RevendedorLicencias from "@/pages/revendedor/licencias";
import RevendedorClientes from "@/pages/revendedor/clientes";
import ProtectedRoute from "@/components/auth/protected-route";
import NotFound from "@/pages/not-found";

function Router() {
  return (
    <Switch>
      <Route path="/" component={Landing} />
      <Route path="/login" component={Login} />
      
      {/* Admin routes */}
      <Route path="/admin/dashboard">
        <ProtectedRoute requiredRole="admin">
          <AdminDashboard />
        </ProtectedRoute>
      </Route>
      <Route path="/admin/revendedores">
        <ProtectedRoute requiredRole="admin">
          <AdminRevendedores />
        </ProtectedRoute>
      </Route>
      <Route path="/admin/licencias">
        <ProtectedRoute requiredRole="admin">
          <AdminLicencias />
        </ProtectedRoute>
      </Route>
      <Route path="/admin/clientes">
        <ProtectedRoute requiredRole="admin">
          <AdminClientes />
        </ProtectedRoute>
      </Route>
      
      {/* Revendedor routes */}
      <Route path="/revendedor/dashboard">
        <ProtectedRoute requiredRole="revendedor">
          <RevendedorDashboard />
        </ProtectedRoute>
      </Route>
      <Route path="/revendedor/licencias">
        <ProtectedRoute requiredRole="revendedor">
          <RevendedorLicencias />
        </ProtectedRoute>
      </Route>
      <Route path="/revendedor/clientes">
        <ProtectedRoute requiredRole="revendedor">
          <RevendedorClientes />
        </ProtectedRoute>
      </Route>
      
      {/* Fallback to 404 */}
      <Route component={NotFound} />
    </Switch>
  );
}

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Router />
      </TooltipProvider>
    </QueryClientProvider>
  );
}

export default App;
