import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Search, Filter, Key, Calendar, AlertTriangle, CheckCircle, Clock } from "lucide-react";
import { useState } from "react";
import { type Licencia } from "@shared/schema";
import RevendedorLayout from "@/components/layout/revendedor-layout";

export default function RevendedorLicencias() {
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState<string>("all");

  const { data: licencias = [], isLoading } = useQuery<Licencia[]>({
    queryKey: ["/api/licencias"],
  });

  const filteredLicencias = licencias.filter(licencia => {
    const matchesSearch = licencia.producto.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         licencia.codigoLicencia.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = statusFilter === "all" || licencia.estado === statusFilter;
    return matchesSearch && matchesStatus;
  });

  const getStatusBadge = (estado: string) => {
    switch (estado) {
      case "activa":
        return <Badge className="bg-green-100 text-green-800"><CheckCircle className="w-3 h-3 mr-1" />Activa</Badge>;
      case "vencida":
        return <Badge variant="destructive"><AlertTriangle className="w-3 h-3 mr-1" />Vencida</Badge>;
      case "suspendida":
        return <Badge variant="outline" className="border-yellow-500 text-yellow-700"><Clock className="w-3 h-3 mr-1" />Suspendida</Badge>;
      default:
        return <Badge variant="secondary">{estado}</Badge>;
    }
  };

  const getExpirationStatus = (fechaVencimiento: string) => {
    const today = new Date();
    const vencimiento = new Date(fechaVencimiento);
    const diffTime = vencimiento.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) {
      return { status: "expired", days: Math.abs(diffDays), color: "text-red-600" };
    } else if (diffDays <= 30) {
      return { status: "expiring", days: diffDays, color: "text-yellow-600" };
    } else {
      return { status: "valid", days: diffDays, color: "text-green-600" };
    }
  };

  const activeLicencias = licencias.filter(l => l.estado === "activa").length;
  const expiredLicencias = licencias.filter(l => l.estado === "vencida").length;
  const suspendedLicencias = licencias.filter(l => l.estado === "suspendida").length;

  return (
    <RevendedorLayout>
      <div className="p-6">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-bold">Mis Licencias</h1>
        </div>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <Card className="card-hover">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">Licencias Activas</p>
                  <p className="text-2xl font-bold text-green-600">{activeLicencias}</p>
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
                  <p className="text-sm text-gray-600">Licencias Vencidas</p>
                  <p className="text-2xl font-bold text-red-600">{expiredLicencias}</p>
                </div>
                <div className="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                  <AlertTriangle className="text-red-600" size={24} />
                </div>
              </div>
            </CardContent>
          </Card>
          
          <Card className="card-hover">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">Licencias Suspendidas</p>
                  <p className="text-2xl font-bold text-yellow-600">{suspendedLicencias}</p>
                </div>
                <div className="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                  <Clock className="text-yellow-600" size={24} />
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <Card>
          <CardHeader>
            <div className="flex items-center justify-between">
              <CardTitle className="flex items-center gap-2">
                <Key className="w-5 h-5" />
                Lista de Licencias
              </CardTitle>
              <div className="flex items-center space-x-2">
                <div className="relative">
                  <Search className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <Input
                    placeholder="Buscar licencias..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="pl-10 w-64"
                  />
                </div>
                <Select value={statusFilter} onValueChange={setStatusFilter}>
                  <SelectTrigger className="w-32">
                    <Filter className="w-4 h-4 mr-2" />
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">Todos</SelectItem>
                    <SelectItem value="activa">Activa</SelectItem>
                    <SelectItem value="vencida">Vencida</SelectItem>
                    <SelectItem value="suspendida">Suspendida</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
          </CardHeader>
          <CardContent>
            {isLoading ? (
              <div className="text-center py-8">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                <p className="mt-2 text-gray-600">Cargando licencias...</p>
              </div>
            ) : filteredLicencias.length === 0 ? (
              <div className="text-center py-8">
                <Key className="w-12 h-12 text-gray-400 mx-auto mb-4" />
                <p className="text-gray-600">No se encontraron licencias</p>
              </div>
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Producto</TableHead>
                    <TableHead>Código de Licencia</TableHead>
                    <TableHead>Estado</TableHead>
                    <TableHead>Fecha Emisión</TableHead>
                    <TableHead>Vencimiento</TableHead>
                    <TableHead>Tiempo Restante</TableHead>
                    <TableHead>Observaciones</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {filteredLicencias.map((licencia) => {
                    const expirationStatus = getExpirationStatus(licencia.fechaVencimiento);
                    
                    return (
                      <TableRow key={licencia.id}>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <div className="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                              <Key className="w-4 h-4 text-primary" />
                            </div>
                            <span className="font-medium">{licencia.producto}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <code className="bg-gray-100 px-2 py-1 rounded text-sm">
                            {licencia.codigoLicencia}
                          </code>
                        </TableCell>
                        <TableCell>
                          {getStatusBadge(licencia.estado)}
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <Calendar className="w-4 h-4 text-gray-400" />
                            <span>{new Date(licencia.fechaEmision).toLocaleDateString()}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <Calendar className="w-4 h-4 text-gray-400" />
                            <span>{new Date(licencia.fechaVencimiento).toLocaleDateString()}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <span className={`text-sm font-medium ${expirationStatus.color}`}>
                            {expirationStatus.status === "expired" && `Vencida hace ${expirationStatus.days} días`}
                            {expirationStatus.status === "expiring" && `${expirationStatus.days} días restantes`}
                            {expirationStatus.status === "valid" && `${expirationStatus.days} días restantes`}
                          </span>
                        </TableCell>
                        <TableCell>
                          <span className="text-sm text-gray-600 max-w-xs truncate">
                            {licencia.observaciones || "-"}
                          </span>
                        </TableCell>
                      </TableRow>
                    );
                  })}
                </TableBody>
              </Table>
            )}
          </CardContent>
        </Card>
      </div>
    </RevendedorLayout>
  );
}
