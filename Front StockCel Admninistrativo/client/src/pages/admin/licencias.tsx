import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";
import { Textarea } from "@/components/ui/textarea";
import { Plus, Edit, Trash2, Search, Filter } from "lucide-react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { insertLicenciaSchema, type Licencia, type InsertLicencia, type Usuario } from "@shared/schema";
import { apiRequest } from "@/lib/queryClient";
import { useToast } from "@/hooks/use-toast";
import AdminLayout from "@/components/layout/admin-layout";

export default function AdminLicencias() {
  const [isDialogOpen, setIsDialogOpen] = useState(false);
  const [editingLicencia, setEditingLicencia] = useState<Licencia | null>(null);
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState<string>("all");
  const queryClient = useQueryClient();
  const { toast } = useToast();

  const { data: licencias = [], isLoading } = useQuery<Licencia[]>({
    queryKey: ["/api/licencias"],
  });

  const { data: usuarios = [] } = useQuery<Usuario[]>({
    queryKey: ["/api/usuarios"],
  });

  const form = useForm<InsertLicencia>({
    resolver: zodResolver(insertLicenciaSchema),
    defaultValues: {
      idUsuario: 0,
      producto: "",
      codigoLicencia: "",
      estado: "activa",
      fechaVencimiento: "",
      observaciones: "",
    },
  });

  const createMutation = useMutation({
    mutationFn: async (data: InsertLicencia) => {
      const response = await apiRequest("POST", "/api/licencias", data);
      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["/api/licencias"] });
      setIsDialogOpen(false);
      form.reset();
      toast({
        title: "Éxito",
        description: "Licencia creada correctamente",
      });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Error al crear licencia",
        variant: "destructive",
      });
    },
  });

  const updateMutation = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: Partial<InsertLicencia> }) => {
      const response = await apiRequest("PUT", `/api/licencias/${id}`, data);
      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["/api/licencias"] });
      setIsDialogOpen(false);
      setEditingLicencia(null);
      form.reset();
      toast({
        title: "Éxito",
        description: "Licencia actualizada correctamente",
      });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Error al actualizar licencia",
        variant: "destructive",
      });
    },
  });

  const deleteMutation = useMutation({
    mutationFn: async (id: number) => {
      await apiRequest("DELETE", `/api/licencias/${id}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["/api/licencias"] });
      toast({
        title: "Éxito",
        description: "Licencia eliminada correctamente",
      });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Error al eliminar licencia",
        variant: "destructive",
      });
    },
  });

  const onSubmit = (data: InsertLicencia) => {
    if (editingLicencia) {
      updateMutation.mutate({ id: editingLicencia.id, data });
    } else {
      createMutation.mutate(data);
    }
  };

  const handleEdit = (licencia: Licencia) => {
    setEditingLicencia(licencia);
    form.reset({
      idUsuario: licencia.idUsuario,
      producto: licencia.producto,
      codigoLicencia: licencia.codigoLicencia,
      estado: licencia.estado,
      fechaVencimiento: licencia.fechaVencimiento,
      observaciones: licencia.observaciones || "",
    });
    setIsDialogOpen(true);
  };

  const handleDelete = (id: number) => {
    if (confirm("¿Estás seguro de que quieres eliminar esta licencia?")) {
      deleteMutation.mutate(id);
    }
  };

  const filteredLicencias = licencias.filter(licencia => {
    const matchesSearch = licencia.producto.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         licencia.codigoLicencia.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = statusFilter === "all" || licencia.estado === statusFilter;
    return matchesSearch && matchesStatus;
  });

  const getStatusBadge = (estado: string) => {
    switch (estado) {
      case "activa":
        return <Badge className="bg-green-100 text-green-800">Activa</Badge>;
      case "vencida":
        return <Badge variant="destructive">Vencida</Badge>;
      case "suspendida":
        return <Badge variant="outline" className="border-yellow-500 text-yellow-700">Suspendida</Badge>;
      default:
        return <Badge variant="secondary">{estado}</Badge>;
    }
  };

  return (
    <AdminLayout>
      <div className="p-6">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-bold">Gestión de Licencias</h1>
          <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
            <DialogTrigger asChild>
              <Button onClick={() => {
                setEditingLicencia(null);
                form.reset();
              }}>
                <Plus className="w-4 h-4 mr-2" />
                Nueva Licencia
              </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
              <DialogHeader>
                <DialogTitle>
                  {editingLicencia ? "Editar Licencia" : "Crear Licencia"}
                </DialogTitle>
              </DialogHeader>
              <Form {...form}>
                <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                  <FormField
                    control={form.control}
                    name="idUsuario"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>Revendedor</FormLabel>
                        <Select onValueChange={(value) => field.onChange(parseInt(value))} value={field.value.toString()}>
                          <FormControl>
                            <SelectTrigger>
                              <SelectValue placeholder="Seleccionar revendedor" />
                            </SelectTrigger>
                          </FormControl>
                          <SelectContent>
                            {usuarios.map((usuario) => (
                              <SelectItem key={usuario.id} value={usuario.id.toString()}>
                                {usuario.nombre} - {usuario.email}
                              </SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                  
                  <FormField
                    control={form.control}
                    name="producto"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>Producto</FormLabel>
                        <FormControl>
                          <Input placeholder="Nombre del producto" {...field} />
                        </FormControl>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                  
                  <FormField
                    control={form.control}
                    name="codigoLicencia"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>Código de Licencia</FormLabel>
                        <FormControl>
                          <Input placeholder="XXXX-XXXX-XXXX-XXXX" {...field} />
                        </FormControl>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                  
                  <FormField
                    control={form.control}
                    name="estado"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>Estado</FormLabel>
                        <Select onValueChange={field.onChange} defaultValue={field.value}>
                          <FormControl>
                            <SelectTrigger>
                              <SelectValue placeholder="Seleccionar estado" />
                            </SelectTrigger>
                          </FormControl>
                          <SelectContent>
                            <SelectItem value="activa">Activa</SelectItem>
                            <SelectItem value="vencida">Vencida</SelectItem>
                            <SelectItem value="suspendida">Suspendida</SelectItem>
                          </SelectContent>
                        </Select>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                  
                  <FormField
                    control={form.control}
                    name="fechaVencimiento"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>Fecha de Vencimiento</FormLabel>
                        <FormControl>
                          <Input type="date" {...field} />
                        </FormControl>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                  
                  <FormField
                    control={form.control}
                    name="observaciones"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>Observaciones</FormLabel>
                        <FormControl>
                          <Textarea placeholder="Observaciones adicionales..." {...field} />
                        </FormControl>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                  
                  <div className="flex justify-end space-x-2">
                    <Button
                      type="button"
                      variant="outline"
                      onClick={() => setIsDialogOpen(false)}
                    >
                      Cancelar
                    </Button>
                    <Button
                      type="submit"
                      disabled={createMutation.isPending || updateMutation.isPending}
                    >
                      {editingLicencia ? "Actualizar" : "Crear"}
                    </Button>
                  </div>
                </form>
              </Form>
            </DialogContent>
          </Dialog>
        </div>

        <Card>
          <CardHeader>
            <div className="flex items-center justify-between">
              <CardTitle>Lista de Licencias</CardTitle>
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
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Producto</TableHead>
                    <TableHead>Código</TableHead>
                    <TableHead>Revendedor</TableHead>
                    <TableHead>Estado</TableHead>
                    <TableHead>Vencimiento</TableHead>
                    <TableHead>Fecha Emisión</TableHead>
                    <TableHead>Acciones</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {filteredLicencias.map((licencia) => {
                    const usuario = usuarios.find(u => u.id === licencia.idUsuario);
                    return (
                      <TableRow key={licencia.id}>
                        <TableCell className="font-medium">{licencia.producto}</TableCell>
                        <TableCell className="font-mono text-sm">{licencia.codigoLicencia}</TableCell>
                        <TableCell>{usuario?.nombre || "N/A"}</TableCell>
                        <TableCell>
                          {getStatusBadge(licencia.estado)}
                        </TableCell>
                        <TableCell>
                          {new Date(licencia.fechaVencimiento).toLocaleDateString()}
                        </TableCell>
                        <TableCell>
                          {new Date(licencia.fechaEmision).toLocaleDateString()}
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleEdit(licencia)}
                            >
                              <Edit className="w-4 h-4" />
                            </Button>
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleDelete(licencia.id)}
                              className="text-red-600 hover:text-red-700"
                            >
                              <Trash2 className="w-4 h-4" />
                            </Button>
                          </div>
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
    </AdminLayout>
  );
}
