import Image from "next/image"
import brandLogo from "../public/images/Logo/Overworld_Logotype_Red.png"

const Header = () => {
  return (
    <nav className="absolute top-0 w-full">
      <div className="container mx-auto px-6 pt-4 lg:pt-8">
        <div className="flex justify-between items-center">
          <Image src={brandLogo} alt="overworld" width={100} height={0} />
          <button className="hidden md:block bg-btnBg px-4 py-2 text-slate-50 rounded-lg font-medium">
            VERIFY TWITTER
          </button>
        </div>
      </div>
    </nav>
  )
}

export default Header
