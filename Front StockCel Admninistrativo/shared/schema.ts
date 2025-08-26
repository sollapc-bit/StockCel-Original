import { mysqlTable, text, int, boolean, timestamp, date, mysqlEnum } from "drizzle-orm/mysql-core";
import { relations } from "drizzle-orm";
import { createInsertSchema } from "drizzle-zod";
import { z } from "zod";

// Enums
export const roleEnum = mysqlEnum("role", ["admin", "revendedor"]);
export const estadoLicenciaEnum = mysqlEnum("estado_licencia", ["activa", "vencida", "suspendida"]);

// Users table
export const usuarios = mysqlTable("usuarios", {
  id: int("id").primaryKey().autoincrement(),
  nombre: text("nombre").notNull(),
  email: text("email").notNull(),
  telefono: text("telefono"),
  empresa: text("empresa"),
  password: text("password").notNull(),
  rol: roleEnum.default("revendedor").notNull(),
  fechaRegistro: timestamp("fecha_registro").defaultNow().notNull(),
});

// Licenses table
export const licencias = mysqlTable("licencias", {
  id: int("id").primaryKey().autoincrement(),
  idUsuario: int("id_usuario").references(() => usuarios.id).notNull(),
  producto: text("producto").notNull(),
  codigoLicencia: text("codigo_licencia").notNull(),
  estado: estadoLicenciaEnum.default("activa").notNull(),
  fechaEmision: timestamp("fecha_emision").defaultNow().notNull(),
  fechaVencimiento: date("fecha_vencimiento").notNull(),
  observaciones: text("observaciones"),
});

// Final clients table
export const clientesFinales = mysqlTable("clientes_finales", {
  id: int("id").primaryKey().autoincrement(),
  idLicencia: int("id_licencia").references(() => licencias.id).notNull(),
  nombreCliente: text("nombre_cliente").notNull(),
  emailCliente: text("email_cliente").notNull(),
  telefonoCliente: text("telefono_cliente"),
  domicilio: text("domicilio"),
  fechaAlta: timestamp("fecha_alta").defaultNow().notNull(),
});

// Relations
export const usuariosRelations = relations(usuarios, ({ many }) => ({
  licencias: many(licencias),
}));

export const licenciasRelations = relations(licencias, ({ one, many }) => ({
  usuario: one(usuarios, {
    fields: [licencias.idUsuario],
    references: [usuarios.id],
  }),
  clientesFinales: many(clientesFinales),
}));

export const clientesFinalesRelations = relations(clientesFinales, ({ one }) => ({
  licencia: one(licencias, {
    fields: [clientesFinales.idLicencia],
    references: [licencias.id],
  }),
}));

// Insert schemas
export const insertUsuarioSchema = createInsertSchema(usuarios).omit({
  id: true,
  fechaRegistro: true,
});

export const insertLicenciaSchema = createInsertSchema(licencias).omit({
  id: true,
  fechaEmision: true,
});

export const insertClienteFinalSchema = createInsertSchema(clientesFinales).omit({
  id: true,
  fechaAlta: true,
});

// Login schema
export const loginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(1),
});

// Types
export type Usuario = typeof usuarios.$inferSelect;
export type InsertUsuario = z.infer<typeof insertUsuarioSchema>;
export type Licencia = typeof licencias.$inferSelect;
export type InsertLicencia = z.infer<typeof insertLicenciaSchema>;
export type ClienteFinal = typeof clientesFinales.$inferSelect;
export type InsertClienteFinal = z.infer<typeof insertClienteFinalSchema>;
export type LoginData = z.infer<typeof loginSchema>;
