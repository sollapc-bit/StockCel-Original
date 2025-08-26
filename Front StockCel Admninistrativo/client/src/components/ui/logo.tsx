import logoImage from "@assets/logo_1752645386390.png";

export default function Logo({ className = "h-10 w-auto" }: { className?: string }) {
  return (
    <img 
      src={logoImage} 
      alt="SoftwarePar" 
      className={`object-contain ${className}`}
    />
  );
}
