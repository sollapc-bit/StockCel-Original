import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";
import { Badge } from "@/components/ui/badge";
import { Key, UsersRound, TrendingUp, AlertTriangle, Calendar, CheckCircle } from "lucide-react";
import RevendedorLayout from "@/components/layout/revendedor-layout";
import { Skeleton } from "@/components/ui/skeleton";
import { type Licencia, type ClienteFinal } from "@shared/schema";

interface DashboardStats {
  totalLicencias: number;
  totalClientes: number;
  licenciasActivas: number;
  licenciasVencidas: number;
  licenciasSuspendidas: number;
}

export default function RevendedorDashboard() {
  const { data: stats, isLoading: statsLoading } = useQuery<DashboardStats>({
    queryKey: ["/api/dashboard/stats"],
  });

  const { data: licencias = [], isLoading: licenciasLoading } = useQuery<Licencia[]>({
    queryKey: ["/api/licencias"],
  });

  const { data: clientes = [], isLoading: clientesLoading } = useQuery<ClienteFinal[]>({
    queryKey: ["/api/clientes"],
  });

  const isLoading = statsLoading || licenciasLoading || clientesLoading;

  // Get licenses expiring soon (within 30 days)
  const licenciasProximasVencer = licencias.filter(licencia => {
    const today = new Date();
    const vencimiento = new Date(licencia.fechaVencimiento);
    const diffTime = vencimiento.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 30 && diffDays > 0 && licencia.estado === "activa";
  });

  // Get recent clients (last 30 days)
  const clientesRecientes = clientes.filter(cliente => {
    const today = new Date();
    const fechaAlta = new Date(cliente.fechaAlta);
    const diffTime = today.getTime() - fechaAlta.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 30;
  });

  if (isLoading) {
    return (
      <RevendedorLayout>
        <div className="p-6">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {[...Array(4)].map((_, i) => (
              <Card key={i}>
                <CardContent className="p-6">
                  <div className="flex items-center justify-between">
                    <div>
                      <Skeleton className="h-4 w-24 mb-2" />
                      <Skeleton className="h-8 w-16" />
                    </div>
                    <Skeleton className="h-12 w-12 rounded-full" />
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
          
          <div className="grid lg:grid-cols-2 gap-6">
            <Card>
              <CardHeader>
                <Skeleton className="h-6 w-48" />
              </CardHeader>
              <CardContent>
                <Skeleton className="h-64 w-full" />
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader>
                <Skeleton className="h-6 w-48" />
              </CardHeader>
              <CardContent>
                <Skeleton className="h-64 w-full" />
              </CardContent>
            </Card>
          </div>
        </div>
      </RevendedorLayout>
    );
  }

  return (
    <RevendedorLayout>
      <div className="p-6">
        {/* Welcome Section */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900 mb-2">¡Bienvenido a tu Dashboard!</h1>
          <p className="text-gray-600">Aquí tienes un resumen de tus licencias y clientes</p>
        </div>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <Card className="card-hover">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">Mis Licencias</p>
                  <p className="text-2xl font-bold text-gray-900">
                    {stats?.totalLicencias || 0}
                  </p>
                </div>
                <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                  <Key className="text-primary" size={24} />
                </div>
              </div>
            </CardContent>
          </Card>
          
          <Card className="card-hover">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">Licencias Activas</p>
                  <p className="text-2xl font-bold text-gray-900">
                    {stats?.licenciasActivas || 0}
                  </p>
                </div>
                <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                  <CheckCircle className="text-green-600" size={24} />
                </div>
              </div>
            </CardContent>
          </Card>
          
          <Card className="card-hover">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">Mis Clientes</p>
                  <p className="text-2xl font-bold text-gray-900">
                    {stats?.totalClientes || 0}
                  </p>
                </div>
                <div className="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center">
                  <UsersRound className="text-secondary" size={24} />
                </div>
              </div>
            </CardContent>
          </Card>
          
          <Card className="card-hover">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">Nuevos Este Mes</p>
                  <p className="text-2xl font-bold text-gray-900">
                    {clientesRecientes.length}
                  </p>
                </div>
                <div className="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center">
                  <TrendingUp className="text-accent" size={24} />
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
        
        {/* Charts and Details */}
        <div className="grid lg:grid-cols-2 gap-6">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <TrendingUp className="w-5 h-5" />
                Estado de mis Licencias
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <span className="text-sm font-medium">Activas</span>
                  <Badge variant="secondary" className="bg-green-100 text-green-800">
                    {stats?.licenciasActivas || 0}
                  </Badge>
                </div>
                <Progress 
                  value={stats ? (stats.licenciasActivas / stats.totalLicencias) * 100 : 0} 
                  className="h-2"
                />
                
                <div className="flex items-center justify-between">
                  <span className="text-sm font-medium">Vencidas</span>
                  <Badge variant="destructive">
                    {stats?.licenciasVencidas || 0}
                  </Badge>
                </div>
                <Progress 
                  value={stats ? (stats.licenciasVencidas / stats.totalLicencias) * 100 : 0} 
                  className="h-2"
                />
                
                <div className="flex items-center justify-between">
                  <span className="text-sm font-medium">Suspendidas</span>
                  <Badge variant="outline" className="border-yellow-500 text-yellow-700">
                    {stats?.licenciasSuspendidas || 0}
                  </Badge>
                </div>
                <Progress 
                  value={stats ? (stats.licenciasSuspendidas / stats.totalLicencias) * 100 : 0} 
                  className="h-2"
                />
              </div>
            </CardContent>
          </Card>
          
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <AlertTriangle className="w-5 h-5" />
                Notificaciones Importantes
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {licenciasProximasVencer.length > 0 && (
                  <div className="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                    <Calendar className="w-5 h-5 text-yellow-500 mt-0.5" />
                    <div>
                      <p className="text-sm font-medium text-yellow-900">Licencias por vencer</p>
                      <p className="text-sm text-yellow-700">
                        {licenciasProximasVencer.length} licencia{licenciasProximasVencer.length > 1 ? 's' : ''} vence{licenciasProximasVencer.length > 1 ? 'n' : ''} pronto
                      </p>
                    </div>
                  </div>
                )}
                
                {stats?.licenciasVencidas && stats.licenciasVencidas > 0 && (
                  <div className="flex items-start space-x-3 p-3 bg-red-50 rounded-lg">
                    <AlertTriangle className="w-5 h-5 text-red-500 mt-0.5" />
                    <div>
                      <p className="text-sm font-medium text-red-900">Licencias vencidas</p>
                      <p className="text-sm text-red-700">
                        {stats.licenciasVencidas} licencia{stats.licenciasVencidas > 1 ? 's' : ''} requiere{stats.licenciasVencidas > 1 ? 'n' : ''} renovación
                      </p>
                    </div>
                  </div>
                )}
                
                {clientesRecientes.length > 0 && (
                  <div className="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                    <UsersRound className="w-5 h-5 text-green-500 mt-0.5" />
                    <div>
                      <p className="text-sm font-medium text-green-900">Nuevos clientes</p>
                      <p className="text-sm text-green-700">
                        {clientesRecientes.length} cliente{clientesRecientes.length > 1 ? 's' : ''} nuevo{clientesRecientes.length > 1 ? 's' : ''} este mes
                      </p>
                    </div>
                  </div>
                )}
                
                {licenciasProximasVencer.length === 0 && (!stats?.licenciasVencidas || stats.licenciasVencidas === 0) && clientesRecientes.length === 0 && (
                  <div className="text-center py-8">
                    <CheckCircle className="w-12 h-12 text-green-500 mx-auto mb-4" />
                    <p className="text-gray-600">¡Todo está en orden!</p>
                    <p className="text-sm text-gray-500">No hay notificaciones pendientes</p>
                  </div>
                )}
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Recent Activity */}
        <Card className="mt-6">
          <CardHeader>
            <CardTitle>Actividad Reciente</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {clientesRecientes.slice(0, 5).map((cliente) => (
                <div key={cliente.id} className="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                  <div className="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                    <UsersRound className="w-4 h-4 text-primary" />
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium">{cliente.nombreCliente}</p>
                    <p className="text-xs text-gray-500">
                      Cliente agregado el {new Date(cliente.fechaAlta).toLocaleDateString()}
                    </p>
                  </div>
                </div>
              ))}
              
              {clientesRecientes.length === 0 && (
                <div className="text-center py-8">
                  <p className="text-gray-600">No hay actividad reciente</p>
                </div>
              )}
            </div>
          </CardContent>
        </Card>
      </div>
    </RevendedorLayout>
  );
}
