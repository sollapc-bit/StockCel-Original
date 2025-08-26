import { usuarios, licencias, clientesFinales, type Usuario, type InsertUsuario, type Licencia, type InsertLicencia, type ClienteFinal, type InsertClienteFinal } from "@shared/schema";
import { db } from "./db";
import { eq, and, desc } from "drizzle-orm";
import * as bcrypt from "bcrypt";

export interface IStorage {
  // User operations
  getUser(id: number): Promise<Usuario | undefined>;
  getUserByEmail(email: string): Promise<Usuario | undefined>;
  createUser(user: InsertUsuario): Promise<Usuario>;
  updateUser(id: number, user: Partial<InsertUsuario>): Promise<Usuario>;
  deleteUser(id: number): Promise<void>;
  getAllRevendedores(): Promise<Usuario[]>;
  
  // License operations
  getLicencia(id: number): Promise<Licencia | undefined>;
  getLicenciasByUsuario(usuarioId: number): Promise<Licencia[]>;
  createLicencia(licencia: InsertLicencia): Promise<Licencia>;
  updateLicencia(id: number, licencia: Partial<InsertLicencia>): Promise<Licencia>;
  deleteLicencia(id: number): Promise<void>;
  getAllLicencias(): Promise<Licencia[]>;
  
  // Client operations
  getClienteFinal(id: number): Promise<ClienteFinal | undefined>;
  getClientesByLicencia(licenciaId: number): Promise<ClienteFinal[]>;
  createClienteFinal(cliente: InsertClienteFinal): Promise<ClienteFinal>;
  updateClienteFinal(id: number, cliente: Partial<InsertClienteFinal>): Promise<ClienteFinal>;
  deleteClienteFinal(id: number): Promise<void>;
  getAllClientes(): Promise<ClienteFinal[]>;
  
  // Auth operations
  verifyPassword(email: string, password: string): Promise<Usuario | null>;
}

export class DatabaseStorage implements IStorage {
  async getUser(id: number): Promise<Usuario | undefined> {
    const [user] = await db.select().from(usuarios).where(eq(usuarios.id, id));
    return user || undefined;
  }

  async getUserByEmail(email: string): Promise<Usuario | undefined> {
    const [user] = await db.select().from(usuarios).where(eq(usuarios.email, email));
    return user || undefined;
  }

  async createUser(insertUser: InsertUsuario): Promise<Usuario> {
    const hashedPassword = await bcrypt.hash(insertUser.password, 10);
    const [user] = await db
      .insert(usuarios)
      .values({ ...insertUser, password: hashedPassword })
      .returning();
    return user;
  }

  async updateUser(id: number, updateUser: Partial<InsertUsuario>): Promise<Usuario> {
    const updateData = { ...updateUser };
    if (updateData.password) {
      updateData.password = await bcrypt.hash(updateData.password, 10);
    }
    const [user] = await db
      .update(usuarios)
      .set(updateData)
      .where(eq(usuarios.id, id))
      .returning();
    return user;
  }

  async deleteUser(id: number): Promise<void> {
    await db.delete(usuarios).where(eq(usuarios.id, id));
  }

  async getAllRevendedores(): Promise<Usuario[]> {
    return await db.select().from(usuarios).where(eq(usuarios.rol, "revendedor")).orderBy(desc(usuarios.fechaRegistro));
  }

  async getLicencia(id: number): Promise<Licencia | undefined> {
    const [licencia] = await db.select().from(licencias).where(eq(licencias.id, id));
    return licencia || undefined;
  }

  async getLicenciasByUsuario(usuarioId: number): Promise<Licencia[]> {
    return await db.select().from(licencias).where(eq(licencias.idUsuario, usuarioId)).orderBy(desc(licencias.fechaEmision));
  }

  async createLicencia(insertLicencia: InsertLicencia): Promise<Licencia> {
    const [licencia] = await db
      .insert(licencias)
      .values(insertLicencia)
      .returning();
    return licencia;
  }

  async updateLicencia(id: number, updateLicencia: Partial<InsertLicencia>): Promise<Licencia> {
    const [licencia] = await db
      .update(licencias)
      .set(updateLicencia)
      .where(eq(licencias.id, id))
      .returning();
    return licencia;
  }

  async deleteLicencia(id: number): Promise<void> {
    await db.delete(licencias).where(eq(licencias.id, id));
  }

  async getAllLicencias(): Promise<Licencia[]> {
    return await db.select().from(licencias).orderBy(desc(licencias.fechaEmision));
  }

  async getClienteFinal(id: number): Promise<ClienteFinal | undefined> {
    const [cliente] = await db.select().from(clientesFinales).where(eq(clientesFinales.id, id));
    return cliente || undefined;
  }

  async getClientesByLicencia(licenciaId: number): Promise<ClienteFinal[]> {
    return await db.select().from(clientesFinales).where(eq(clientesFinales.idLicencia, licenciaId)).orderBy(desc(clientesFinales.fechaAlta));
  }

  async createClienteFinal(insertCliente: InsertClienteFinal): Promise<ClienteFinal> {
    const [cliente] = await db
      .insert(clientesFinales)
      .values(insertCliente)
      .returning();
    return cliente;
  }

  async updateClienteFinal(id: number, updateCliente: Partial<InsertClienteFinal>): Promise<ClienteFinal> {
    const [cliente] = await db
      .update(clientesFinales)
      .set(updateCliente)
      .where(eq(clientesFinales.id, id))
      .returning();
    return cliente;
  }

  async deleteClienteFinal(id: number): Promise<void> {
    await db.delete(clientesFinales).where(eq(clientesFinales.id, id));
  }

  async getAllClientes(): Promise<ClienteFinal[]> {
    return await db.select().from(clientesFinales).orderBy(desc(clientesFinales.fechaAlta));
  }

  async verifyPassword(email: string, password: string): Promise<Usuario | null> {
    const user = await this.getUserByEmail(email);
    if (!user) return null;
    
    const isValid = await bcrypt.compare(password, user.password);
    return isValid ? user : null;
  }
}

export const storage = new DatabaseStorage();
