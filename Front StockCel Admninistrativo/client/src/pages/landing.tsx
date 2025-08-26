import { Link } from "wouter";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import Logo from "@/components/ui/logo";
import LogoFooter from "@/components/ui/logo-footer";
import WhatsAppButton from "@/components/ui/whatsapp-button";
import { 
  Users, 
  Key, 
  BarChart3, 
  CheckCircle, 
  Phone, 
  Mail, 
  MapPin,
  Menu,
  X,
  ChevronDown,
  Settings,
  Award,
  Shield
} from "lucide-react";
import { useState } from "react";

export default function Landing() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation */}
      <nav className="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-lg">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <Logo className="h-10 w-auto" />
            </div>
            
            <div className="hidden md:flex items-center space-x-8">
              <a href="#inicio" className="text-gray-700 hover:text-primary transition-colors">Inicio</a>
              <a href="#servicios" className="text-gray-700 hover:text-primary transition-colors">Servicios</a>
              <a href="#beneficios" className="text-gray-700 hover:text-primary transition-colors">Beneficios</a>
              <a href="#portfolio" className="text-gray-700 hover:text-primary transition-colors">Portfolio</a>
              <a href="#contacto" className="text-gray-700 hover:text-primary transition-colors">Contacto</a>
              <Button asChild>
                <Link href="/login">Acceder</Link>
              </Button>
            </div>
            
            <div className="md:hidden">
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="text-gray-700 hover:text-primary transition-colors"
              >
                {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
              </button>
            </div>
          </div>
        </div>
        
        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className="md:hidden bg-white border-t">
            <div className="px-2 pt-2 pb-3 space-y-1">
              <a href="#inicio" className="block px-3 py-2 text-gray-700 hover:text-primary transition-colors">Inicio</a>
              <a href="#servicios" className="block px-3 py-2 text-gray-700 hover:text-primary transition-colors">Servicios</a>
              <a href="#beneficios" className="block px-3 py-2 text-gray-700 hover:text-primary transition-colors">Beneficios</a>
              <a href="#portfolio" className="block px-3 py-2 text-gray-700 hover:text-primary transition-colors">Portfolio</a>
              <a href="#contacto" className="block px-3 py-2 text-gray-700 hover:text-primary transition-colors">Contacto</a>
              <div className="px-3 py-2">
                <Button asChild className="w-full">
                  <Link href="/login">Acceder</Link>
                </Button>
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Hero Section */}
      <section id="inicio" className="relative min-h-screen flex items-center hero-bg">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
          <div className="animate-slide-up">
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
              Sistemas Administrativos
              <span className="block text-accent">a Medida</span>
            </h1>
            <p className="text-xl sm:text-2xl mb-8 max-w-4xl mx-auto opacity-90">
              Desarrollamos soluciones completas para la gestión de stock, ventas, clientes, facturación y reportes. Adaptadas a tu negocio con modelo de licenciamiento para revendedores.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button asChild size="lg" className="bg-white text-primary hover:bg-gray-100">
                <Link href="/login">Acceder al Sistema</Link>
              </Button>
              <Button variant="outline" size="lg" className="border-white text-white hover:bg-white hover:text-primary">
                <a href="#contacto">Solicitar Presupuesto</a>
              </Button>
            </div>
          </div>
        </div>
        
        {/* Floating Animation */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-float">
          <ChevronDown className="text-white" size={32} />
        </div>
      </section>

      {/* Services Section */}
      <section id="servicios" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Nuestros Servicios
            </h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
              En SoftwarePar nos especializamos en el desarrollo de sistemas administrativos a medida para negocios, emprendedores, empresas y tiendas
            </p>
          </div>
          
          <div className="grid md:grid-cols-3 gap-8">
            <Card className="card-hover">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-primary/10 rounded-lg flex items-center justify-center mb-6">
                  <Settings className="text-primary" size={32} />
                </div>
                <h3 className="text-xl font-semibold mb-4">Desarrollo a Medida</h3>
                <p className="text-gray-600">
                  Creamos sistemas completos y personalizados para gestión de stock, ventas, clientes, facturación y reportes adaptados a tu rubro específico
                </p>
              </CardContent>
            </Card>
            
            <Card className="card-hover">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-secondary/10 rounded-lg flex items-center justify-center mb-6">
                  <Award className="text-secondary" size={32} />
                </div>
                <h3 className="text-xl font-semibold mb-4">Adaptación Empresarial</h3>
                <p className="text-gray-600">
                  Adaptamos cada sistema a las necesidades específicas de tu empresa, garantizando que se ajuste perfectamente a tus procesos de negocio
                </p>
              </CardContent>
            </Card>
            
            <Card className="card-hover">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-accent/10 rounded-lg flex items-center justify-center mb-6">
                  <Shield className="text-accent" size={32} />
                </div>
                <h3 className="text-xl font-semibold mb-4">Licenciamiento para Revendedores</h3>
                <p className="text-gray-600">
                  Si querés revender nuestros sistemas, te ofrecemos licencias exclusivas para distribuir bajo tu nombre con soporte completo
                </p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Licensing Model Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-secondary text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold mb-6">Modelo de Licenciamiento</h2>
            <p className="text-xl opacity-90 max-w-3xl mx-auto">
              Tres pasos sencillos para expandir tu negocio con nuestros sistemas
            </p>
          </div>
          
          <div className="grid md:grid-cols-3 gap-8">
            <div className="bg-white/10 backdrop-blur-md rounded-xl p-8 text-center">
              <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <Settings className="text-white" size={32} />
              </div>
              <h3 className="text-2xl font-bold mb-4">1. Desarrollamos</h3>
              <p className="text-lg opacity-90">
                Creamos el sistema administrativo personalizado para tu rubro y necesidades específicas
              </p>
            </div>
            
            <div className="bg-white/10 backdrop-blur-md rounded-xl p-8 text-center">
              <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <Award className="text-white" size={32} />
              </div>
              <h3 className="text-2xl font-bold mb-4">2. Adaptamos</h3>
              <p className="text-lg opacity-90">
                Lo personalizamos completamente para tu empresa, ajustándolo a tus procesos y marca
              </p>
            </div>
            
            <div className="bg-white/10 backdrop-blur-md rounded-xl p-8 text-center">
              <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <Shield className="text-white" size={32} />
              </div>
              <h3 className="text-2xl font-bold mb-4">3. Licenciamos</h3>
              <p className="text-lg opacity-90">
                Te otorgamos licencias exclusivas para que puedas revenderlo bajo tu nombre con soporte completo
              </p>
            </div>
          </div>
          
          <div className="text-center mt-12">
            <Button size="lg" className="bg-white text-primary hover:bg-gray-100">
              <a href="#contacto">Consultar Planes de Licencias</a>
            </Button>
          </div>
        </div>
      </section>

      {/* Benefits Section */}
      <section id="beneficios" className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <h2 className="text-4xl font-bold text-gray-900 mb-6">
                ¿Por qué elegir SoftwarePar?
              </h2>
              <div className="space-y-6">
                <div className="flex items-start space-x-4">
                  <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <CheckCircle className="text-green-600" size={20} />
                  </div>
                  <div>
                    <h3 className="font-semibold text-lg mb-2">✔️ Desarrollamos el sistema</h3>
                    <p className="text-gray-600">Creamos soluciones completas adaptadas a tu rubro específico</p>
                  </div>
                </div>
                
                <div className="flex items-start space-x-4">
                  <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <CheckCircle className="text-green-600" size={20} />
                  </div>
                  <div>
                    <h3 className="font-semibold text-lg mb-2">✔️ Lo adaptamos a tu empresa</h3>
                    <p className="text-gray-600">Personalización completa para tus procesos y necesidades</p>
                  </div>
                </div>
                
                <div className="flex items-start space-x-4">
                  <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <CheckCircle className="text-green-600" size={20} />
                  </div>
                  <div>
                    <h3 className="font-semibold text-lg mb-2">✔️ Licencias para revender</h3>
                    <p className="text-gray-600">Modelo flexible para distribuir bajo tu nombre con soporte completo</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div className="relative">
              <Card className="gradient-bg text-white">
                <CardContent className="p-8">
                  <div className="grid grid-cols-2 gap-4 mb-6">
                    <div className="bg-white/20 rounded-lg p-4">
                      <Users className="mb-2" size={24} />
                      <div className="text-sm">Revendedores</div>
                      <div className="text-2xl font-bold">245</div>
                    </div>
                    <div className="bg-white/20 rounded-lg p-4">
                      <Key className="mb-2" size={24} />
                      <div className="text-sm">Licencias</div>
                      <div className="text-2xl font-bold">1,840</div>
                    </div>
                  </div>
                  <div className="text-center">
                    <BarChart3 className="mx-auto mb-4" size={48} />
                    <div className="text-lg font-semibold">Control Total</div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>

      {/* Portfolio Section */}
      <section id="portfolio" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Funcionalidades del Sistema
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
              Explora las características principales de nuestro sistema de gestión
            </p>
          </div>
          
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <Card className="card-hover overflow-hidden">
              <div className="h-48 gradient-bg flex items-center justify-center">
                <BarChart3 className="text-white" size={48} />
              </div>
              <CardContent className="p-6">
                <h3 className="text-xl font-semibold mb-2">Dashboard Ejecutivo</h3>
                <p className="text-gray-600">Panel principal con métricas clave y visualización de datos</p>
              </CardContent>
            </Card>
            
            <Card className="card-hover overflow-hidden">
              <div className="h-48 bg-gradient-to-br from-secondary to-accent flex items-center justify-center">
                <Settings className="text-white" size={48} />
              </div>
              <CardContent className="p-6">
                <h3 className="text-xl font-semibold mb-2">Gestión de Usuarios</h3>
                <p className="text-gray-600">CRUD completo para administrar revendedores y permisos</p>
              </CardContent>
            </Card>
            
            <Card className="card-hover overflow-hidden">
              <div className="h-48 bg-gradient-to-br from-accent to-purple-500 flex items-center justify-center">
                <Award className="text-white" size={48} />
              </div>
              <CardContent className="p-6">
                <h3 className="text-xl font-semibold mb-2">Control de Licencias</h3>
                <p className="text-gray-600">Asignación, renovación y monitoreo de licencias</p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Lo que dicen nuestros clientes
            </h2>
          </div>
          
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <Card>
              <CardContent className="p-8">
                <div className="flex items-center mb-4">
                  <div className="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                    JS
                  </div>
                  <div className="ml-4">
                    <h4 className="font-semibold">Juan Sánchez</h4>
                    <p className="text-gray-600 text-sm">CEO, TechSolutions</p>
                  </div>
                </div>
                <p className="text-gray-600 italic">
                  "SoftwarePar revolucionó nuestra gestión de licencias. La interfaz es intuitiva y el soporte excepcional."
                </p>
              </CardContent>
            </Card>
            
            <Card>
              <CardContent className="p-8">
                <div className="flex items-center mb-4">
                  <div className="w-12 h-12 bg-secondary rounded-full flex items-center justify-center text-white font-bold">
                    MR
                  </div>
                  <div className="ml-4">
                    <h4 className="font-semibold">María Rodríguez</h4>
                    <p className="text-gray-600 text-sm">Gerente, SoftwareDistrib</p>
                  </div>
                </div>
                <p className="text-gray-600 italic">
                  "El sistema nos permite controlar eficientemente más de 500 licencias. Muy recomendado."
                </p>
              </CardContent>
            </Card>
            
            <Card>
              <CardContent className="p-8">
                <div className="flex items-center mb-4">
                  <div className="w-12 h-12 bg-accent rounded-full flex items-center justify-center text-white font-bold">
                    CL
                  </div>
                  <div className="ml-4">
                    <h4 className="font-semibold">Carlos López</h4>
                    <p className="text-gray-600 text-sm">Director, InfoSystems</p>
                  </div>
                </div>
                <p className="text-gray-600 italic">
                  "La implementación fue sencilla y los resultados inmediatos. Excelente inversión."
                </p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contacto" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Contáctanos
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
              ¿Listo para mejorar tu gestión de licencias? Hablemos
            </p>
          </div>
          
          <div className="grid lg:grid-cols-2 gap-12">
            <div>
              <h3 className="text-2xl font-semibold mb-6">Información de Contacto</h3>
              <div className="space-y-4">
                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                    <Phone className="text-primary" size={20} />
                  </div>
                  <div>
                    <p className="font-semibold">Teléfono</p>
                    <p className="text-gray-600">+54 11 7062 7214</p>
                  </div>
                </div>
                
                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                    <Mail className="text-primary" size={20} />
                  </div>
                  <div>
                    <p className="font-semibold">Email</p>
                    <p className="text-gray-600">info@softwarepar.com</p>
                  </div>
                </div>
                
                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                    <MapPin className="text-primary" size={20} />
                  </div>
                  <div>
                    <p className="font-semibold">Ubicación</p>
                    <p className="text-gray-600">Buenos Aires, Argentina</p>
                  </div>
                </div>
              </div>
            </div>
            
            <Card>
              <CardContent className="p-8">
                <form className="space-y-6">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <Input placeholder="Tu nombre" />
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <Input type="email" placeholder="tu@email.com" />
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Mensaje</label>
                    <Textarea rows={4} placeholder="Cuéntanos sobre tu proyecto..." />
                  </div>
                  
                  <Button type="submit" className="w-full">
                    Enviar Mensaje
                  </Button>
                </form>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-4 gap-8">
            <div>
              <div className="mb-4">
                <LogoFooter className="h-12 w-auto" />
              </div>
              <p className="text-gray-400">
                Sistemas administrativos personalizados para empresas con modelo de licenciamiento flexible
              </p>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Producto</h4>
              <ul className="space-y-2 text-gray-400">
                <li><a href="#" className="hover:text-white transition-colors">Características</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Precios</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Seguridad</a></li>
              </ul>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Empresa</h4>
              <ul className="space-y-2 text-gray-400">
                <li><a href="#" className="hover:text-white transition-colors">Sobre Nosotros</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Blog</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Contacto</a></li>
              </ul>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Soporte</h4>
              <ul className="space-y-2 text-gray-400">
                <li><a href="#" className="hover:text-white transition-colors">Documentación</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Centro de Ayuda</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Estado del Sistema</a></li>
              </ul>
            </div>
          </div>
          
          <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 SoftwarePar. Todos los derechos reservados.</p>
          </div>
        </div>
      </footer>

      <WhatsAppButton />
    </div>
  );
}
