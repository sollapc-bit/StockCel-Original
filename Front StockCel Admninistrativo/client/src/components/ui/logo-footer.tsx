import logoFooterImage from "@assets/logo_blanco_para_fondo_azul_1752711102592.png";

export default function LogoFooter({ className = "h-8 w-auto" }: { className?: string }) {
  return (
    <img 
      src={logoFooterImage} 
      alt="SoftwarePar" 
      className={`object-contain ${className}`}
    />
  );
}