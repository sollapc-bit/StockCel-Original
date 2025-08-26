import type { Express } from "express";
import { createServer, type Server } from "http";
import { storage } from "./storage";
import { insertUsuarioSchema, insertLicenciaSchema, insertClienteFinalSchema, loginSchema } from "@shared/schema";
import session from "express-session";
import { z } from "zod";

declare module "express-session" {
  interface SessionData {
    user: {
      id: number;
      email: string;
      rol: string;
    };
  }
}

export async function registerRoutes(app: Express): Promise<Server> {
  // Session configuration
  app.use(session({
    secret: process.env.SESSION_SECRET || "softwarepar-secret-key",
    resave: false,
    saveUninitialized: false,
    cookie: {
      secure: process.env.NODE_ENV === "production",
      httpOnly: true,
      maxAge: 24 * 60 * 60 * 1000 // 24 hours
    }
  }));

  // Auth middleware
  const requireAuth = (req: any, res: any, next: any) => {
    if (!req.session?.user) {
      return res.status(401).json({ message: "No autorizado" });
    }
    next();
  };

  const requireAdmin = (req: any, res: any, next: any) => {
    if (!req.session?.user || req.session.user.rol !== "admin") {
      return res.status(403).json({ message: "Acceso denegado" });
    }
    next();
  };

  // Auth routes
  app.post("/api/auth/login", async (req, res) => {
    try {
      const { email, password } = loginSchema.parse(req.body);
      const user = await storage.verifyPassword(email, password);
      
      if (!user) {
        return res.status(401).json({ message: "Credenciales inválidas" });
      }

      req.session.user = {
        id: user.id,
        email: user.email,
        rol: user.rol
      };

      res.json({ 
        user: {
          id: user.id,
          nombre: user.nombre,
          email: user.email,
          rol: user.rol
        }
      });
    } catch (error) {
      res.status(400).json({ message: "Datos inválidos" });
    }
  });

  app.post("/api/auth/logout", (req, res) => {
    req.session.destroy(() => {
      res.json({ message: "Sesión cerrada" });
    });
  });

  app.get("/api/auth/me", requireAuth, async (req, res) => {
    try {
      const user = await storage.getUser(req.session.user!.id);
      if (!user) {
        return res.status(404).json({ message: "Usuario no encontrado" });
      }
      res.json({
        id: user.id,
        nombre: user.nombre,
        email: user.email,
        rol: user.rol
      });
    } catch (error) {
      res.status(500).json({ message: "Error interno del servidor" });
    }
  });

  // Usuario routes
  app.get("/api/usuarios", requireAdmin, async (req, res) => {
    try {
      const usuarios = await storage.getAllRevendedores();
      res.json(usuarios);
    } catch (error) {
      res.status(500).json({ message: "Error al obtener usuarios" });
    }
  });

  app.post("/api/usuarios", requireAdmin, async (req, res) => {
    try {
      const userData = insertUsuarioSchema.parse(req.body);
      const usuario = await storage.createUser(userData);
      res.status(201).json(usuario);
    } catch (error) {
      if (error instanceof z.ZodError) {
        res.status(400).json({ message: "Datos inválidos", errors: error.errors });
      } else {
        res.status(500).json({ message: "Error al crear usuario" });
      }
    }
  });

  app.put("/api/usuarios/:id", requireAdmin, async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const userData = insertUsuarioSchema.partial().parse(req.body);
      const usuario = await storage.updateUser(id, userData);
      res.json(usuario);
    } catch (error) {
      if (error instanceof z.ZodError) {
        res.status(400).json({ message: "Datos inválidos", errors: error.errors });
      } else {
        res.status(500).json({ message: "Error al actualizar usuario" });
      }
    }
  });

  app.delete("/api/usuarios/:id", requireAdmin, async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      await storage.deleteUser(id);
      res.json({ message: "Usuario eliminado" });
    } catch (error) {
      res.status(500).json({ message: "Error al eliminar usuario" });
    }
  });

  // Licencia routes
  app.get("/api/licencias", requireAuth, async (req, res) => {
    try {
      let licencias;
      if (req.session.user!.rol === "admin") {
        licencias = await storage.getAllLicencias();
      } else {
        licencias = await storage.getLicenciasByUsuario(req.session.user!.id);
      }
      res.json(licencias);
    } catch (error) {
      res.status(500).json({ message: "Error al obtener licencias" });
    }
  });

  app.post("/api/licencias", requireAdmin, async (req, res) => {
    try {
      const licenciaData = insertLicenciaSchema.parse(req.body);
      const licencia = await storage.createLicencia(licenciaData);
      res.status(201).json(licencia);
    } catch (error) {
      if (error instanceof z.ZodError) {
        res.status(400).json({ message: "Datos inválidos", errors: error.errors });
      } else {
        res.status(500).json({ message: "Error al crear licencia" });
      }
    }
  });

  app.put("/api/licencias/:id", requireAdmin, async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const licenciaData = insertLicenciaSchema.partial().parse(req.body);
      const licencia = await storage.updateLicencia(id, licenciaData);
      res.json(licencia);
    } catch (error) {
      if (error instanceof z.ZodError) {
        res.status(400).json({ message: "Datos inválidos", errors: error.errors });
      } else {
        res.status(500).json({ message: "Error al actualizar licencia" });
      }
    }
  });

  app.delete("/api/licencias/:id", requireAdmin, async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      await storage.deleteLicencia(id);
      res.json({ message: "Licencia eliminada" });
    } catch (error) {
      res.status(500).json({ message: "Error al eliminar licencia" });
    }
  });

  // Cliente routes
  app.get("/api/clientes", requireAuth, async (req, res) => {
    try {
      let clientes;
      if (req.session.user!.rol === "admin") {
        clientes = await storage.getAllClientes();
      } else {
        // Get clients for revendedor's licenses
        const licencias = await storage.getLicenciasByUsuario(req.session.user!.id);
        clientes = [];
        for (const licencia of licencias) {
          const clientesLicencia = await storage.getClientesByLicencia(licencia.id);
          clientes.push(...clientesLicencia);
        }
      }
      res.json(clientes);
    } catch (error) {
      res.status(500).json({ message: "Error al obtener clientes" });
    }
  });

  app.post("/api/clientes", requireAuth, async (req, res) => {
    try {
      const clienteData = insertClienteFinalSchema.parse(req.body);
      
      // Verify that the license belongs to the user (if not admin)
      if (req.session.user!.rol !== "admin") {
        const licencia = await storage.getLicencia(clienteData.idLicencia);
        if (!licencia || licencia.idUsuario !== req.session.user!.id) {
          return res.status(403).json({ message: "No autorizado para esta licencia" });
        }
      }
      
      const cliente = await storage.createClienteFinal(clienteData);
      res.status(201).json(cliente);
    } catch (error) {
      if (error instanceof z.ZodError) {
        res.status(400).json({ message: "Datos inválidos", errors: error.errors });
      } else {
        res.status(500).json({ message: "Error al crear cliente" });
      }
    }
  });

  app.put("/api/clientes/:id", requireAuth, async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const clienteData = insertClienteFinalSchema.partial().parse(req.body);
      
      // Verify ownership if not admin
      if (req.session.user!.rol !== "admin") {
        const cliente = await storage.getClienteFinal(id);
        if (!cliente) {
          return res.status(404).json({ message: "Cliente no encontrado" });
        }
        const licencia = await storage.getLicencia(cliente.idLicencia);
        if (!licencia || licencia.idUsuario !== req.session.user!.id) {
          return res.status(403).json({ message: "No autorizado" });
        }
      }
      
      const cliente = await storage.updateClienteFinal(id, clienteData);
      res.json(cliente);
    } catch (error) {
      if (error instanceof z.ZodError) {
        res.status(400).json({ message: "Datos inválidos", errors: error.errors });
      } else {
        res.status(500).json({ message: "Error al actualizar cliente" });
      }
    }
  });

  app.delete("/api/clientes/:id", requireAuth, async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      
      // Verify ownership if not admin
      if (req.session.user!.rol !== "admin") {
        const cliente = await storage.getClienteFinal(id);
        if (!cliente) {
          return res.status(404).json({ message: "Cliente no encontrado" });
        }
        const licencia = await storage.getLicencia(cliente.idLicencia);
        if (!licencia || licencia.idUsuario !== req.session.user!.id) {
          return res.status(403).json({ message: "No autorizado" });
        }
      }
      
      await storage.deleteClienteFinal(id);
      res.json({ message: "Cliente eliminado" });
    } catch (error) {
      res.status(500).json({ message: "Error al eliminar cliente" });
    }
  });

  // Dashboard stats
  app.get("/api/dashboard/stats", requireAuth, async (req, res) => {
    try {
      if (req.session.user!.rol === "admin") {
        const revendedores = await storage.getAllRevendedores();
        const licencias = await storage.getAllLicencias();
        const clientes = await storage.getAllClientes();
        
        const stats = {
          totalRevendedores: revendedores.length,
          totalLicencias: licencias.length,
          totalClientes: clientes.length,
          licenciasActivas: licencias.filter(l => l.estado === "activa").length,
          licenciasVencidas: licencias.filter(l => l.estado === "vencida").length,
          licenciasSuspendidas: licencias.filter(l => l.estado === "suspendida").length
        };
        
        res.json(stats);
      } else {
        const licencias = await storage.getLicenciasByUsuario(req.session.user!.id);
        let totalClientes = 0;
        for (const licencia of licencias) {
          const clientesLicencia = await storage.getClientesByLicencia(licencia.id);
          totalClientes += clientesLicencia.length;
        }
        
        const stats = {
          totalLicencias: licencias.length,
          totalClientes,
          licenciasActivas: licencias.filter(l => l.estado === "activa").length,
          licenciasVencidas: licencias.filter(l => l.estado === "vencida").length,
          licenciasSuspendidas: licencias.filter(l => l.estado === "suspendida").length
        };
        
        res.json(stats);
      }
    } catch (error) {
      res.status(500).json({ message: "Error al obtener estadísticas" });
    }
  });

  const httpServer = createServer(app);
  return httpServer;
}
