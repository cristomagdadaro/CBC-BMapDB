import { useState, useEffect } from 'react';
import { 
  Menu, X, ChevronDown, Search, Database, MapPin, 
  HelpCircle, Info, LogIn, ArrowRight, Filter,
  BarChart3, Users, Sprout, FileText, Phone, Mail,
  Facebook, Instagram, Globe, ChevronRight,
  Leaf, BookOpen, Download,
  ExternalLink, PlayCircle
} from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { Separator } from '@/components/ui/separator';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ScrollArea } from '@/components/ui/scroll-area';
import './App.css';

// Types
interface NavItem {
  label: string;
  href: string;
  icon?: React.ReactNode;
  children?: { label: string; href: string; description?: string }[];
}

interface DatabaseCard {
  title: string;
  description: string;
  image: string;
  href: string;
  stats: { label: string; value: string }[];
  tags: string[];
}

interface FAQItem {
  question: string;
  answer: string;
}

interface Institute {
  id: string;
  name: string;
  region: string;
  province: string;
  commodities: string[];
}

// Data
const navItems: NavItem[] = [
  { label: 'Home', href: '#home' },
  { 
    label: 'Browse Data', 
    href: '#databases',
    children: [
      { label: 'Plant Breeders Map', href: '#breeders-map', description: 'Geographic distribution of plant breeders' },
      { label: 'Biotech TWG Database', href: '#twg-db', description: 'Technical Working Group projects' },
    ]
  },
  { label: 'Search', href: '#search', icon: <Search className="w-4 h-4" /> },
  { 
    label: 'About PIN', 
    href: '#about',
    children: [
      { label: 'What is PIN?', href: '#what-is-pin', description: 'Learn about our mission' },
      { label: 'Contributors', href: '#contributors', description: 'Meet our partners' },
      { label: 'Terms of Use', href: '#terms', description: 'Usage policies' },
    ]
  },
  { 
    label: 'Help', 
    href: '#help',
    children: [
      { label: 'FAQ', href: '#faq', description: 'Frequently asked questions' },
      { label: 'Contact Us', href: '#contact', description: 'Get in touch' },
      { label: 'User Guide', href: '#guide', description: 'How to use PIN' },
    ]
  },
];

const databaseCards: DatabaseCard[] = [
  {
    title: 'Plant Breeders Map',
    description: 'Centralized repository for crop biotechnology commodities, providing essential data and resources in one accessible platform. Explore comprehensive information on germplasm, genetic traits, NSIC registrations, plant variety protections, and GM regulatory approvals.',
    image: '/breeders-map-card.jpg',
    href: '#breeders-map',
    stats: [
      { label: 'Institutes', value: '70+' },
      { label: 'Commodities', value: '25+' },
      { label: 'Provinces', value: '45+' },
    ],
    tags: ['Geographic Data', 'Institutes', 'Commodities']
  },
  {
    title: 'Biotech TWG Database',
    description: 'A robust system designed to manage and organize biotechnology-related projects efficiently. Serves as a centralized hub for storing, tracking, and accessing essential data from various technical working groups.',
    image: '/twg-db-card.jpg',
    href: '#twg-db',
    stats: [
      { label: 'Projects', value: '120+' },
      { label: 'TWGs', value: '15' },
      { label: 'Researchers', value: '300+' },
    ],
    tags: ['Projects', 'Research', 'Collaboration']
  },
];

const faqItems: FAQItem[] = [
  {
    question: 'What is PIN and who can use it?',
    answer: 'PIN (Plant Breeders and Innovators Network) is a comprehensive platform designed for researchers, extension workers, students, and public stakeholders involved in crop biotechnology. It provides access to valuable data resources, research collaborations, and geographic information about plant breeding activities across the Philippines.'
  },
  {
    question: 'How do I access the databases?',
    answer: 'You can access our databases by navigating to the "Browse Data" section. The Plant Breeders Map provides geographic visualization of breeding activities, while the Biotech TWG Database offers detailed project information. Some features may require registration for full access.'
  },
  {
    question: 'Is the data on PIN regularly updated?',
    answer: 'Yes, our databases are regularly updated with contributions from partner institutions, research centers, and government agencies. We ensure data accuracy through a verification process involving our network of experts and contributors.'
  },
  {
    question: 'How can I contribute data to PIN?',
    answer: 'To contribute data, you need to register for an account and request contributor access. Our team will review your application and provide guidelines for data submission. We welcome contributions from recognized research institutions and breeding programs.'
  },
  {
    question: 'What support is available for new users?',
    answer: 'We offer comprehensive user guides, video tutorials, and a dedicated support team to help you get started. You can also reach out through our contact form or attend our regular training webinars.'
  },
];

const institutes: Institute[] = [
  { id: '1', name: 'DA - Crop Biotechnology Center', region: 'Central Luzon', province: 'Nueva Ecija', commodities: ['Rice', 'Corn'] },
  { id: '2', name: 'Central Luzon State University', region: 'Central Luzon', province: 'Nueva Ecija', commodities: ['Rice', 'Vegetables'] },
  { id: '3', name: 'University of the Philippines Los Baños', region: 'Calabarzon', province: 'Laguna', commodities: ['Rice', 'Coconut', 'Fruits'] },
  { id: '4', name: 'PhilRice - Central Experiment Station', region: 'Central Luzon', province: 'Nueva Ecija', commodities: ['Rice'] },
  { id: '5', name: 'Benguet State University', region: 'CAR', province: 'Benguet', commodities: ['Vegetables', 'Potato'] },
  { id: '6', name: 'Visayas State University', region: 'Eastern Visayas', province: 'Leyte', commodities: ['Root Crops', 'Vegetables'] },
];

const partners = [
  'DA-CBC', 'PhilRice', 'DOST', 'DA-BAR', 'UPLB', 'CLSU', 'BSU', 'VSU',
  'IRRI', 'SEARCA', 'PCAARRD', 'BPI', 'NSQC', 'DA-RFOs', 'SCUs'
];

// Components
function Navigation() {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <TooltipProvider>
      <header 
        className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
          isScrolled 
            ? 'bg-white/95 backdrop-blur-xl shadow-lg py-3' 
            : 'bg-transparent py-5'
        }`}
        role="banner"
      >
        <nav className="section-padding" role="navigation" aria-label="Main navigation">
          <div className="container-custom flex items-center justify-between">
            {/* Logo */}
            <a 
              href="#home" 
              className="flex items-center gap-3 group focus-ring rounded-lg"
              aria-label="PIN - Plant Breeders and Innovators Network Home"
            >
              <div className="w-10 h-10 bg-pin-green rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <Sprout className="w-6 h-6 text-white" aria-hidden="true" />
              </div>
              <div className="hidden sm:block">
                <p className={`text-xs font-medium transition-colors ${isScrolled ? 'text-gray-500' : 'text-white/80'}`}>
                  DA - Crop Biotechnology Center
                </p>
                <p className={`text-sm font-bold font-display transition-colors ${isScrolled ? 'text-pin-green' : 'text-white'}`}>
                  Plant Breeders and Innovators Network
                </p>
              </div>
            </a>

            {/* Desktop Navigation */}
            <div className="hidden lg:flex items-center gap-1">
              {navItems.map((item) => (
                <div 
                  key={item.label}
                  className="relative"
                  onMouseEnter={() => item.children && setActiveDropdown(item.label)}
                  onMouseLeave={() => setActiveDropdown(null)}
                >
                  <a
                    href={item.href}
                    className={`px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5 focus-ring ${
                      isScrolled 
                        ? 'text-gray-700 hover:text-pin-green hover:bg-pin-green-light' 
                        : 'text-white/90 hover:text-white hover:bg-white/10'
                    }`}
                    aria-expanded={item.children ? activeDropdown === item.label : undefined}
                    aria-haspopup={item.children ? 'true' : undefined}
                  >
                    {item.label}
                    {item.children && (
                      <ChevronDown className={`w-4 h-4 transition-transform ${activeDropdown === item.label ? 'rotate-180' : ''}`} aria-hidden="true" />
                    )}
                  </a>
                  
                  {/* Dropdown */}
                  {item.children && activeDropdown === item.label && (
                    <div className="absolute top-full left-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden animate-fade-up">
                      {item.children.map((child) => (
                        <a
                          key={child.label}
                          href={child.href}
                          className="block px-5 py-4 hover:bg-pin-green-light transition-colors group"
                        >
                          <p className="font-medium text-gray-900 group-hover:text-pin-green transition-colors">
                            {child.label}
                          </p>
                          {child.description && (
                            <p className="text-sm text-gray-500 mt-1">{child.description}</p>
                          )}
                        </a>
                      ))}
                    </div>
                  )}
                </div>
              ))}
            </div>

            {/* CTA Buttons */}
            <div className="hidden lg:flex items-center gap-3">
              <Tooltip>
                <TooltipTrigger asChild>
                  <button 
                    className={`p-2 rounded-lg transition-colors focus-ring ${
                      isScrolled ? 'hover:bg-gray-100 text-gray-600' : 'hover:bg-white/10 text-white'
                    }`}
                    aria-label="Search"
                  >
                    <Search className="w-5 h-5" />
                  </button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>Search databases</p>
                </TooltipContent>
              </Tooltip>
              
              <a 
                href="#login" 
                className={`flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium transition-all focus-ring ${
                  isScrolled 
                    ? 'bg-pin-green text-white hover:bg-pin-green-dark hover:shadow-lg' 
                    : 'bg-white text-pin-green hover:bg-white/90'
                }`}
              >
                <LogIn className="w-4 h-4" aria-hidden="true" />
                Log in
              </a>
            </div>

            {/* Mobile Menu Button */}
            <button
              className={`lg:hidden p-2 rounded-lg transition-colors focus-ring ${
                isScrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'
              }`}
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              aria-expanded={isMobileMenuOpen}
              aria-label={isMobileMenuOpen ? 'Close menu' : 'Open menu'}
              aria-controls="mobile-menu"
            >
              {isMobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </nav>

        {/* Mobile Menu */}
        {isMobileMenuOpen && (
          <div 
            id="mobile-menu"
            className="lg:hidden bg-white border-t border-gray-100 animate-slide-in-right"
            role="menu"
          >
            <div className="section-padding py-4 space-y-1">
              {navItems.map((item) => (
                <div key={item.label}>
                  <a
                    href={item.href}
                    className="block px-4 py-3 rounded-lg text-gray-700 hover:bg-pin-green-light hover:text-pin-green font-medium transition-colors"
                    onClick={() => !item.children && setIsMobileMenuOpen(false)}
                    role="menuitem"
                  >
                    {item.label}
                  </a>
                  {item.children && (
                    <div className="pl-4 space-y-1">
                      {item.children.map((child) => (
                        <a
                          key={child.label}
                          href={child.href}
                          className="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-pin-green-light hover:text-pin-green transition-colors"
                          onClick={() => setIsMobileMenuOpen(false)}
                          role="menuitem"
                        >
                          {child.label}
                        </a>
                      ))}
                    </div>
                  )}
                </div>
              ))}
              <div className="pt-4 border-t border-gray-100">
                <a 
                  href="#login" 
                  className="flex items-center justify-center gap-2 w-full px-4 py-3 bg-pin-green text-white rounded-lg font-medium hover:bg-pin-green-dark transition-colors"
                  role="menuitem"
                >
                  <LogIn className="w-4 h-4" />
                  Log in
                </a>
              </div>
            </div>
          </div>
        )}
      </header>
    </TooltipProvider>
  );
}

function HeroSection() {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    setIsVisible(true);
  }, []);

  return (
    <section 
      id="home" 
      className="relative min-h-screen flex items-center justify-center overflow-hidden"
      aria-labelledby="hero-heading"
    >
      {/* Background Image */}
      <div className="absolute inset-0 z-0">
        <img 
          src="/hero-bg.jpg" 
          alt="Farmers working in rice paddy field" 
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70" aria-hidden="true" />
      </div>

      {/* Floating Particles */}
      <div className="absolute inset-0 z-10 pointer-events-none overflow-hidden" aria-hidden="true">
        {[...Array(6)].map((_, i) => (
          <div
            key={i}
            className="absolute w-2 h-2 bg-white/20 rounded-full animate-float"
            style={{
              left: `${15 + i * 15}%`,
              top: `${20 + (i % 3) * 25}%`,
              animationDelay: `${i * 0.5}s`,
            }}
          />
        ))}
      </div>

      {/* Content */}
      <div className="relative z-20 section-padding pt-32 pb-20">
        <div className="container-custom text-center">
          {/* Badge */}
          <div 
            className={`inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-8 transition-all duration-700 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'
            }`}
          >
            <Leaf className="w-4 h-4 text-pin-lime" aria-hidden="true" />
            <span>Empowering Agricultural Innovation</span>
          </div>

          {/* Main Heading */}
          <h1 
            id="hero-heading"
            className={`text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white font-display leading-tight mb-6 transition-all duration-700 delay-100 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
            }`}
          >
            <span className="block">Plant Breeders and</span>
            <span className="block text-pin-lime">Innovators Network</span>
          </h1>

          {/* Subheading */}
          <p 
            className={`text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-10 transition-all duration-700 delay-200 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
            }`}
          >
            Empowering crop biotechnology research with innovation, one discovery at a time. 
            Access comprehensive data, connect with researchers, and drive agricultural advancement.
          </p>

          {/* CTAs */}
          <div 
            className={`flex flex-col sm:flex-row items-center justify-center gap-4 transition-all duration-700 delay-300 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
            }`}
          >
            <a 
              href="#databases" 
              className="group flex items-center gap-2 px-8 py-4 bg-pin-green hover:bg-pin-green-dark text-white rounded-xl font-semibold transition-all hover:shadow-xl hover:-translate-y-1 focus-ring"
            >
              <Database className="w-5 h-5" aria-hidden="true" />
              Explore Databases
              <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" aria-hidden="true" />
            </a>
            <a 
              href="#about" 
              className="group flex items-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white rounded-xl font-semibold transition-all focus-ring"
            >
              <PlayCircle className="w-5 h-5" aria-hidden="true" />
              Learn More
            </a>
          </div>

          {/* Stats */}
          <div 
            className={`grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 max-w-3xl mx-auto transition-all duration-700 delay-500 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
            }`}
          >
            {[
              { value: '70+', label: 'Partner Institutes' },
              { value: '25+', label: 'Commodities' },
              { value: '120+', label: 'Research Projects' },
              { value: '300+', label: 'Researchers' },
            ].map((stat, index) => (
              <div key={index} className="text-center">
                <p className="text-3xl sm:text-4xl font-bold text-pin-lime font-display">{stat.value}</p>
                <p className="text-sm text-white/70 mt-1">{stat.label}</p>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Scroll Indicator */}
      <div 
        className="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 animate-bounce"
        aria-hidden="true"
      >
        <ChevronDown className="w-6 h-6 text-white/50" />
      </div>
    </section>
  );
}

function DatabaseCardsSection() {
  const [hoveredCard, setHoveredCard] = useState<number | null>(null);

  return (
    <section id="databases" className="py-20 lg:py-32 bg-white" aria-labelledby="databases-heading">
      <div className="section-padding">
        <div className="container-custom">
          {/* Section Header */}
          <div className="text-center max-w-2xl mx-auto mb-16">
            <Badge className="mb-4 bg-pin-green-light text-pin-green hover:bg-pin-green-light">
              <Database className="w-3 h-3 mr-1" />
              Data Resources
            </Badge>
            <h2 id="databases-heading" className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
              Explore Our Databases
            </h2>
            <p className="text-lg text-gray-600">
              Access comprehensive agricultural data through our specialized databases designed 
              for researchers, breeders, and innovators.
            </p>
          </div>

          {/* Database Cards */}
          <div className="grid lg:grid-cols-2 gap-8">
            {databaseCards.map((card, index) => (
              <a
                key={card.title}
                href={card.href}
                className="group relative block rounded-2xl overflow-hidden shadow-card hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 focus-ring"
                onMouseEnter={() => setHoveredCard(index)}
                onMouseLeave={() => setHoveredCard(null)}
                aria-labelledby={`card-title-${index}`}
              >
                {/* Image */}
                <div className="relative h-80 lg:h-96 overflow-hidden">
                  <img 
                    src={card.image} 
                    alt="" 
                    className={`w-full h-full object-cover transition-transform duration-700 ${
                      hoveredCard === index ? 'scale-110' : 'scale-100'
                    }`}
                    aria-hidden="true"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent" aria-hidden="true" />
                </div>

                {/* Content */}
                <div className="absolute inset-0 flex flex-col justify-end p-6 lg:p-8">
                  {/* Tags */}
                  <div className="flex flex-wrap gap-2 mb-4">
                    {card.tags.map((tag) => (
                      <span 
                        key={tag} 
                        className="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-medium rounded-full"
                      >
                        {tag}
                      </span>
                    ))}
                  </div>

                  {/* Title */}
                  <h3 
                    id={`card-title-${index}`}
                    className="text-2xl lg:text-3xl font-bold text-white mb-3 group-hover:text-pin-lime transition-colors"
                  >
                    {card.title}
                  </h3>

                  {/* Description */}
                  <p className="text-white/80 text-sm lg:text-base mb-6 line-clamp-3">
                    {card.description}
                  </p>

                  {/* Stats */}
                  <div className="flex items-center gap-6 mb-6">
                    {card.stats.map((stat) => (
                      <div key={stat.label}>
                        <p className="text-2xl font-bold text-pin-lime font-display">{stat.value}</p>
                        <p className="text-xs text-white/70">{stat.label}</p>
                      </div>
                    ))}
                  </div>

                  {/* CTA */}
                  <div className="flex items-center gap-2 text-white font-medium group-hover:text-pin-lime transition-colors">
                    <span>Explore Database</span>
                    <ArrowRight className="w-5 h-5 group-hover:translate-x-2 transition-transform" aria-hidden="true" />
                  </div>
                </div>
              </a>
            ))}
          </div>

          {/* Coming Soon */}
          <div className="mt-12 text-center">
            <p className="text-gray-500 flex items-center justify-center gap-2">
              <Info className="w-4 h-4" aria-hidden="true" />
              More databases coming soon. Stay tuned for updates!
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}

function MapVisualizationSection() {
  const [selectedRegion, setSelectedRegion] = useState<string>('all');
  const [selectedCommodity, setSelectedCommodity] = useState<string>('all');
  const [selectedInstitute, setSelectedInstitute] = useState<Institute | null>(null);
  const [viewMode, setViewMode] = useState<'map' | 'list'>('map');

  const filteredInstitutes = institutes.filter(institute => {
    const matchesRegion = selectedRegion === 'all' || institute.region === selectedRegion;
    const matchesCommodity = selectedCommodity === 'all' || institute.commodities.includes(selectedCommodity);
    return matchesRegion && matchesCommodity;
  });

  const regions = ['all', ...Array.from(new Set(institutes.map(i => i.region)))];
  const commodities = ['all', ...Array.from(new Set(institutes.flatMap(i => i.commodities)))];

  return (
    <section id="breeders-map" className="py-20 lg:py-32 bg-pin-gray" aria-labelledby="map-heading">
      <div className="section-padding">
        <div className="container-custom">
          {/* Section Header */}
          <div className="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
            <div>
              <Badge className="mb-4 bg-pin-green/10 text-pin-green hover:bg-pin-green/10">
                <MapPin className="w-3 h-3 mr-1" />
                Geographic Distribution
              </Badge>
              <h2 id="map-heading" className="text-3xl sm:text-4xl font-bold text-gray-900">
                Plant Breeders Map
              </h2>
              <p className="text-gray-600 mt-2 max-w-xl">
                Explore the geographic distribution of plant breeding institutes across the Philippines.
              </p>
            </div>
            
            {/* View Toggle */}
            <div className="flex items-center gap-2 bg-white p-1 rounded-lg shadow-sm">
              <button
                onClick={() => setViewMode('map')}
                className={`flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-all focus-ring ${
                  viewMode === 'map' 
                    ? 'bg-pin-green text-white' 
                    : 'text-gray-600 hover:bg-gray-100'
                }`}
                aria-pressed={viewMode === 'map'}
              >
                <MapPin className="w-4 h-4" />
                Map View
              </button>
              <button
                onClick={() => setViewMode('list')}
                className={`flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-all focus-ring ${
                  viewMode === 'list' 
                    ? 'bg-pin-green text-white' 
                    : 'text-gray-600 hover:bg-gray-100'
                }`}
                aria-pressed={viewMode === 'list'}
              >
                <BarChart3 className="w-4 h-4" />
                List View
              </button>
            </div>
          </div>

          {/* Filters */}
          <div className="bg-white rounded-xl shadow-card p-4 lg:p-6 mb-6">
            <div className="flex flex-col lg:flex-row gap-4">
              <div className="flex items-center gap-2 text-gray-700 font-medium">
                <Filter className="w-5 h-5" aria-hidden="true" />
                <span>Filter by:</span>
              </div>
              
              <div className="flex-1 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <Select value={selectedRegion} onValueChange={setSelectedRegion}>
                  <SelectTrigger className="w-full" aria-label="Filter by region">
                    <SelectValue placeholder="Select Region" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">All Regions</SelectItem>
                    {regions.filter(r => r !== 'all').map(region => (
                      <SelectItem key={region} value={region}>{region}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>

                <Select value={selectedCommodity} onValueChange={setSelectedCommodity}>
                  <SelectTrigger className="w-full" aria-label="Filter by commodity">
                    <SelectValue placeholder="Select Commodity" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">All Commodities</SelectItem>
                    {commodities.filter(c => c !== 'all').map(commodity => (
                      <SelectItem key={commodity} value={commodity}>{commodity}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>

                <div className="flex items-center gap-4">
                  <button 
                    onClick={() => { setSelectedRegion('all'); setSelectedCommodity('all'); }}
                    className="text-sm text-gray-500 hover:text-pin-green transition-colors focus-ring rounded px-2 py-1"
                  >
                    Clear Filters
                  </button>
                  <span className="text-sm text-gray-500 ml-auto">
                    Showing <strong className="text-pin-green">{filteredInstitutes.length}</strong> institutes
                  </span>
                </div>
              </div>
            </div>
          </div>

          {/* Content */}
          <div className="grid lg:grid-cols-3 gap-6">
            {/* Map or List */}
            <div className="lg:col-span-2">
              {viewMode === 'map' ? (
                <div className="bg-white rounded-xl shadow-card overflow-hidden h-[500px] lg:h-[600px] relative">
                  {/* Simplified Map Visualization */}
                  <div className="absolute inset-0 bg-gradient-to-br from-pin-green-light to-white flex items-center justify-center">
                    <div className="text-center p-8">
                      <div className="w-20 h-20 bg-pin-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <MapPin className="w-10 h-10 text-pin-green" />
                      </div>
                      <h3 className="text-xl font-semibold text-gray-900 mb-2">Interactive Map</h3>
                      <p className="text-gray-600 mb-4">
                        Map visualization showing {filteredInstitutes.length} institutes across the Philippines
                      </p>
                      
                      {/* Simulated Map Markers */}
                      <div className="relative w-full max-w-md mx-auto h-64 bg-pin-green/5 rounded-xl border-2 border-dashed border-pin-green/30 flex items-center justify-center">
                        <div className="grid grid-cols-3 gap-8">
                          {filteredInstitutes.slice(0, 6).map((institute) => (
                            <button
                              key={institute.id}
                              onClick={() => setSelectedInstitute(institute)}
                              className="group relative"
                              aria-label={`View ${institute.name}`}
                            >
                              <div className="relative">
                                <div className="w-4 h-4 bg-pin-green rounded-full group-hover:scale-150 transition-transform" />
                                <div className="absolute inset-0 w-4 h-4 bg-pin-green rounded-full animate-pulse-ring" />
                              </div>
                              <span className="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-gray-600 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                {institute.name.split(' ')[0]}
                              </span>
                            </button>
                          ))}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              ) : (
                <div className="bg-white rounded-xl shadow-card overflow-hidden">
                  <ScrollArea className="h-[500px] lg:h-[600px]">
                    <div className="p-4 space-y-3">
                      {filteredInstitutes.map((institute) => (
                        <button
                          key={institute.id}
                          onClick={() => setSelectedInstitute(institute)}
                          className={`w-full text-left p-4 rounded-lg border-2 transition-all focus-ring ${
                            selectedInstitute?.id === institute.id
                              ? 'border-pin-green bg-pin-green-light'
                              : 'border-gray-100 hover:border-pin-green/30 hover:bg-gray-50'
                          }`}
                        >
                          <div className="flex items-start justify-between">
                            <div>
                              <h4 className="font-semibold text-gray-900">{institute.name}</h4>
                              <p className="text-sm text-gray-500 mt-1">
                                {institute.province}, {institute.region}
                              </p>
                              <div className="flex flex-wrap gap-1 mt-2">
                                {institute.commodities.map(commodity => (
                                  <Badge key={commodity} variant="secondary" className="text-xs">
                                    {commodity}
                                  </Badge>
                                ))}
                              </div>
                            </div>
                            <ChevronRight className="w-5 h-5 text-gray-400" />
                          </div>
                        </button>
                      ))}
                    </div>
                  </ScrollArea>
                </div>
              )}
            </div>

            {/* Institute Details Panel */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-xl shadow-card p-6 h-full">
                {selectedInstitute ? (
                  <div className="animate-fade-in">
                    <div className="flex items-center gap-3 mb-4">
                      <div className="w-12 h-12 bg-pin-green-light rounded-xl flex items-center justify-center">
                        <BuildingIcon className="w-6 h-6 text-pin-green" />
                      </div>
                      <div>
                        <h3 className="font-semibold text-gray-900">{selectedInstitute.name}</h3>
                        <p className="text-sm text-gray-500">{selectedInstitute.region}</p>
                      </div>
                    </div>

                    <Separator className="my-4" />

                    <div className="space-y-4">
                      <div>
                        <p className="text-sm text-gray-500 mb-1">Province</p>
                        <p className="font-medium text-gray-900">{selectedInstitute.province}</p>
                      </div>

                      <div>
                        <p className="text-sm text-gray-500 mb-2">Commodities</p>
                        <div className="flex flex-wrap gap-2">
                          {selectedInstitute.commodities.map(commodity => (
                            <Badge key={commodity} className="bg-pin-green-light text-pin-green">
                              {commodity}
                            </Badge>
                          ))}
                        </div>
                      </div>

                      <div>
                        <p className="text-sm text-gray-500 mb-1">Contact</p>
                        <p className="text-sm text-gray-600">
                          For more information about this institute, please contact the DA-CBC office.
                        </p>
                      </div>
                    </div>

                    <div className="mt-6 space-y-2">
                      <button className="w-full btn-primary text-center">
                        View Full Profile
                      </button>
                      <button 
                        onClick={() => setSelectedInstitute(null)}
                        className="w-full btn-ghost text-center"
                      >
                        Close
                      </button>
                    </div>
                  </div>
                ) : (
                  <div className="text-center py-12">
                    <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <MapPin className="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 className="font-medium text-gray-900 mb-2">Select an Institute</h3>
                    <p className="text-sm text-gray-500">
                      Click on a marker or list item to view detailed information
                    </p>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

function BuildingIcon({ className }: { className?: string }) {
  return (
    <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
      <path d="M3 21h18M9 21V9l-6 4v8M21 21V9l-6 4v8M12 3v18" />
      <path d="M7 13h2M7 17h2M15 13h2M15 17h2" />
    </svg>
  );
}

function AboutSection() {
  const features = [
    {
      icon: <Database className="w-6 h-6" />,
      title: 'Centralized Data',
      description: 'Access comprehensive agricultural data from multiple sources in one platform.',
    },
    {
      icon: <Users className="w-6 h-6" />,
      title: 'Collaboration',
      description: 'Connect with researchers, breeders, and innovators across the Philippines.',
    },
    {
      icon: <MapPin className="w-6 h-6" />,
      title: 'Geographic Insights',
      description: 'Visualize breeding activities and research distribution across regions.',
    },
    {
      icon: <BarChart3 className="w-6 h-6" />,
      title: 'Data Analytics',
      description: 'Analyze trends and patterns to inform research decisions.',
    },
    {
      icon: <Sprout className="w-6 h-6" />,
      title: 'Crop Diversity',
      description: 'Explore information on various crops and breeding programs.',
    },
    {
      icon: <FileText className="w-6 h-6" />,
      title: 'Documentation',
      description: 'Access research papers, reports, and technical documents.',
    },
  ];

  return (
    <section id="about" className="py-20 lg:py-32 bg-white" aria-labelledby="about-heading">
      <div className="section-padding">
        <div className="container-custom">
          <div className="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {/* Content */}
            <div>
              <Badge className="mb-4 bg-pin-green-light text-pin-green hover:bg-pin-green-light">
                <Info className="w-3 h-3 mr-1" />
                About PIN
              </Badge>
              <h2 id="about-heading" className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Empowering Crop Biotechnology Research
              </h2>
              <div className="space-y-4 text-gray-600">
                <p>
                  The Plant Breeders and Innovators Network (PIN) is a specialized online platform 
                  developed by the DA - Crop Biotechnology Center. It serves as a centralized repository 
                  of essential information meticulously curated to support crop biotechnology research 
                  endeavors across the Philippines.
                </p>
                <p>
                  Within this digital resource, you will find a comprehensive collection of data, 
                  tools, and resources designed to facilitate scientific investigations, accelerate 
                  discoveries, and drive innovation in the field of crop biotechnology.
                </p>
              </div>

              <div className="mt-8 flex flex-wrap gap-4">
                <a href="#databases" className="btn-primary inline-flex items-center gap-2">
                  <Database className="w-4 h-4" />
                  Explore Data
                </a>
                <Dialog>
                  <DialogTrigger asChild>
                    <button className="btn-secondary inline-flex items-center gap-2">
                      <PlayCircle className="w-4 h-4" />
                      Watch Video
                    </button>
                  </DialogTrigger>
                  <DialogContent className="max-w-2xl">
                    <DialogHeader>
                      <DialogTitle>About PIN</DialogTitle>
                      <DialogDescription>
                        Learn more about the Plant Breeders and Innovators Network
                      </DialogDescription>
                    </DialogHeader>
                    <div className="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                      <div className="text-center">
                        <PlayCircle className="w-16 h-16 text-pin-green mx-auto mb-4" />
                        <p className="text-gray-600">Video player placeholder</p>
                      </div>
                    </div>
                  </DialogContent>
                </Dialog>
              </div>
            </div>

            {/* Features Grid */}
            <div className="grid sm:grid-cols-2 gap-4">
              {features.map((feature) => (
                <div 
                  key={feature.title}
                  className="p-6 bg-pin-gray rounded-xl hover:bg-pin-green-light transition-colors group"
                >
                  <div className="w-12 h-12 bg-pin-green/10 rounded-xl flex items-center justify-center text-pin-green mb-4 group-hover:bg-pin-green group-hover:text-white transition-colors">
                    {feature.icon}
                  </div>
                  <h3 className="font-semibold text-gray-900 mb-2">{feature.title}</h3>
                  <p className="text-sm text-gray-600">{feature.description}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

function HelpSection() {
  return (
    <section id="help" className="py-20 lg:py-32 bg-pin-gray" aria-labelledby="help-heading">
      <div className="section-padding">
        <div className="container-custom">
          <div className="grid lg:grid-cols-2 gap-12 lg:gap-20">
            {/* FAQ */}
            <div>
              <Badge className="mb-4 bg-pin-green/10 text-pin-green hover:bg-pin-green/10">
                <HelpCircle className="w-3 h-3 mr-1" />
                FAQ
              </Badge>
              <h2 id="help-heading" className="text-3xl sm:text-4xl font-bold text-gray-900 mb-8">
                Frequently Asked Questions
              </h2>
              
              <Accordion type="single" collapsible className="space-y-3">
                {faqItems.map((item, index) => (
                  <AccordionItem 
                    key={index} 
                    value={`item-${index}`}
                    className="bg-white rounded-xl border-none shadow-sm px-6"
                  >
                    <AccordionTrigger className="text-left font-medium text-gray-900 hover:text-pin-green py-4">
                      {item.question}
                    </AccordionTrigger>
                    <AccordionContent className="text-gray-600 pb-4">
                      {item.answer}
                    </AccordionContent>
                  </AccordionItem>
                ))}
              </Accordion>
            </div>

            {/* Contact & Resources */}
            <div>
              <Badge className="mb-4 bg-pin-green/10 text-pin-green hover:bg-pin-green/10">
                <Phone className="w-3 h-3 mr-1" />
                Contact & Resources
              </Badge>
              <h2 className="text-3xl sm:text-4xl font-bold text-gray-900 mb-8">
                Get in Touch
              </h2>

              <div className="space-y-6">
                {/* Contact Cards */}
                <div className="grid sm:grid-cols-2 gap-4">
                  <Card className="hover:shadow-lg transition-shadow">
                    <CardContent className="p-5">
                      <div className="w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green mb-3">
                        <Mail className="w-5 h-5" />
                      </div>
                      <h3 className="font-semibold text-gray-900 mb-1">Email Us</h3>
                      <a 
                        href="mailto:cropbiotechcenter@gmail.com" 
                        className="text-sm text-pin-green hover:underline"
                      >
                        cropbiotechcenter@gmail.com
                      </a>
                    </CardContent>
                  </Card>

                  <Card className="hover:shadow-lg transition-shadow">
                    <CardContent className="p-5">
                      <div className="w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green mb-3">
                        <Phone className="w-5 h-5" />
                      </div>
                      <h3 className="font-semibold text-gray-900 mb-1">Call Us</h3>
                      <a 
                        href="tel:+639088897135" 
                        className="text-sm text-pin-green hover:underline"
                      >
                        0908 889 7135
                      </a>
                    </CardContent>
                  </Card>
                </div>

                {/* User Guide */}
                <Card className="bg-pin-green text-white">
                  <CardContent className="p-6">
                    <div className="flex items-start gap-4">
                      <div className="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <BookOpen className="w-6 h-6" />
                      </div>
                      <div>
                        <h3 className="font-semibold text-lg mb-2">User Guide</h3>
                        <p className="text-white/80 text-sm mb-4">
                          Download our comprehensive user guide to learn how to make the most of PIN's features.
                        </p>
                        <button className="inline-flex items-center gap-2 px-4 py-2 bg-white text-pin-green rounded-lg font-medium hover:bg-white/90 transition-colors focus-ring">
                          <Download className="w-4 h-4" />
                          Download Guide
                        </button>
                      </div>
                    </div>
                  </CardContent>
                </Card>

                {/* Quick Links */}
                <div className="bg-white rounded-xl p-6 shadow-sm">
                  <h3 className="font-semibold text-gray-900 mb-4">Quick Links</h3>
                  <div className="space-y-2">
                    {[
                      { label: 'Terms of Use', href: '#terms' },
                      { label: 'Privacy Policy', href: '#privacy' },
                      { label: 'Data Privacy Notice', href: '#data-privacy' },
                      { label: 'Sitemap', href: '#sitemap' },
                    ].map((link) => (
                      <a
                        key={link.label}
                        href={link.href}
                        className="flex items-center justify-between p-3 rounded-lg hover:bg-pin-green-light transition-colors group"
                      >
                        <span className="text-gray-700 group-hover:text-pin-green transition-colors">
                          {link.label}
                        </span>
                        <ExternalLink className="w-4 h-4 text-gray-400 group-hover:text-pin-green transition-colors" />
                      </a>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

function PartnersSection() {
  return (
    <section className="py-16 bg-white overflow-hidden" aria-labelledby="partners-heading">
      <div className="section-padding mb-8">
        <div className="container-custom text-center">
          <h2 id="partners-heading" className="text-2xl font-bold text-gray-900">
            Our Partners
          </h2>
          <p className="text-gray-600 mt-2">
            Collaborating with leading institutions across the Philippines
          </p>
        </div>
      </div>

      {/* Marquee */}
      <div className="relative">
        <div className="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10" aria-hidden="true" />
        <div className="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10" aria-hidden="true" />
        
        <div className="flex animate-marquee">
          {[...partners, ...partners].map((partner, index) => (
            <div 
              key={index}
              className="flex-shrink-0 mx-8 px-8 py-4 bg-pin-gray rounded-xl hover:bg-pin-green-light transition-colors cursor-pointer group"
            >
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-pin-green/10 rounded-lg flex items-center justify-center group-hover:bg-pin-green transition-colors">
                  <BuildingIcon className="w-5 h-5 text-pin-green group-hover:text-white transition-colors" />
                </div>
                <span className="font-semibold text-gray-700 group-hover:text-pin-green transition-colors whitespace-nowrap">
                  {partner}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

function Footer() {
  const quickLinks = [
    { label: 'Home', href: '#home' },
    { label: 'Browse Data', href: '#databases' },
    { label: 'About PIN', href: '#about' },
    { label: 'Help', href: '#help' },
  ];

  const resources = [
    { label: 'User Guide', href: '#guide' },
    { label: 'FAQ', href: '#faq' },
    { label: 'Terms of Use', href: '#terms' },
    { label: 'Privacy Policy', href: '#privacy' },
  ];

  const governmentLinks = [
    { label: 'Official Gazette', href: 'https://www.officialgazette.gov.ph/' },
    { label: 'Office of the President', href: 'https://op-proper.gov.ph/' },
    { label: 'Department of Agriculture', href: 'https://www.da.gov.ph/' },
    { label: 'DOST', href: 'https://www.dost.gov.ph/' },
  ];

  return (
    <footer className="bg-gray-900 text-white" role="contentinfo">
      {/* Main Footer */}
      <div className="section-padding py-16">
        <div className="container-custom">
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            {/* Brand */}
            <div className="sm:col-span-2 lg:col-span-1">
              <a href="#home" className="flex items-center gap-3 mb-6 focus-ring rounded-lg">
                <div className="w-10 h-10 bg-pin-green rounded-lg flex items-center justify-center">
                  <Sprout className="w-6 h-6 text-white" />
                </div>
                <div>
                  <p className="text-xs text-gray-400">DA - Crop Biotechnology Center</p>
                  <p className="text-sm font-bold font-display">PIN System</p>
                </div>
              </a>
              <p className="text-gray-400 text-sm mb-6">
                Empowering crop biotechnology research with innovation, one discovery at a time.
              </p>
              <div className="flex items-center gap-3">
                <a 
                  href="https://www.facebook.com/DACropBiotechCenter" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pin-green transition-colors focus-ring"
                  aria-label="Facebook"
                >
                  <Facebook className="w-5 h-5" />
                </a>
                <a 
                  href="https://www.instagram.com/da_cbcph/" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pin-green transition-colors focus-ring"
                  aria-label="Instagram"
                >
                  <Instagram className="w-5 h-5" />
                </a>
                <a 
                  href="https://dacbc.philrice.gov.ph" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pin-green transition-colors focus-ring"
                  aria-label="Website"
                >
                  <Globe className="w-5 h-5" />
                </a>
              </div>
            </div>

            {/* Quick Links */}
            <div>
              <h3 className="font-semibold text-lg mb-4">Quick Links</h3>
              <ul className="space-y-3">
                {quickLinks.map((link) => (
                  <li key={link.label}>
                    <a 
                      href={link.href}
                      className="text-gray-400 hover:text-white transition-colors focus-ring rounded"
                    >
                      {link.label}
                    </a>
                  </li>
                ))}
              </ul>
            </div>

            {/* Resources */}
            <div>
              <h3 className="font-semibold text-lg mb-4">Resources</h3>
              <ul className="space-y-3">
                {resources.map((link) => (
                  <li key={link.label}>
                    <a 
                      href={link.href}
                      className="text-gray-400 hover:text-white transition-colors focus-ring rounded"
                    >
                      {link.label}
                    </a>
                  </li>
                ))}
              </ul>
            </div>

            {/* Contact */}
            <div>
              <h3 className="font-semibold text-lg mb-4">Contact Us</h3>
              <div className="space-y-4 text-sm text-gray-400">
                <p>
                  PhilRice Compound, Maligaya<br />
                  Science City of Muñoz<br />
                  Nueva Ecija, Philippines 3119
                </p>
                <p>
                  <a href="tel:+639088897135" className="hover:text-white transition-colors">
                    Mobile: 0908 889 7135
                  </a>
                </p>
                <p>
                  <a href="mailto:cropbiotechcenter@gmail.com" className="hover:text-white transition-colors">
                    cropbiotechcenter@gmail.com
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Government Links */}
      <div className="border-t border-white/10">
        <div className="section-padding py-6">
          <div className="container-custom">
            <div className="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-gray-500">
              <span className="font-medium text-gray-400">Republic of the Philippines:</span>
              {governmentLinks.map((link) => (
                <a
                  key={link.label}
                  href={link.href}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="hover:text-white transition-colors focus-ring rounded"
                >
                  {link.label}
                </a>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Copyright */}
      <div className="border-t border-white/10">
        <div className="section-padding py-6">
          <div className="container-custom text-center text-sm text-gray-500">
            <p>
              © {new Date().getFullYear()} DA - Crop Biotechnology Center. All rights reserved.
            </p>
            <p className="mt-1">
              Plant Breeders and Innovators Network System
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
}

// Main App Component
function App() {
  const [showOnboarding, setShowOnboarding] = useState(false);

  useEffect(() => {
    // Check if user has seen onboarding
    const hasSeenOnboarding = localStorage.getItem('pin-onboarding-seen');
    if (!hasSeenOnboarding) {
      setShowOnboarding(true);
    }
  }, []);

  const dismissOnboarding = () => {
    setShowOnboarding(false);
    localStorage.setItem('pin-onboarding-seen', 'true');
  };

  return (
    <div className="min-h-screen bg-white">
      {/* Skip to main content link for accessibility */}
      <a 
        href="#main-content" 
        className="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-pin-green focus:text-white focus:px-4 focus:py-2 focus:rounded-lg"
      >
        Skip to main content
      </a>

      <Navigation />
      
      <main id="main-content">
        <HeroSection />
        <DatabaseCardsSection />
        <MapVisualizationSection />
        <AboutSection />
        <PartnersSection />
        <HelpSection />
      </main>

      <Footer />

      {/* Onboarding Tooltip */}
      {showOnboarding && (
        <div className="fixed bottom-4 right-4 z-50 max-w-sm animate-fade-up">
          <div className="bg-white rounded-xl shadow-2xl p-5 border border-gray-100">
            <div className="flex items-start gap-3">
              <div className="w-10 h-10 bg-pin-green-light rounded-full flex items-center justify-center flex-shrink-0">
                <HelpCircle className="w-5 h-5 text-pin-green" />
              </div>
              <div>
                <h4 className="font-semibold text-gray-900 mb-1">Welcome to PIN!</h4>
                <p className="text-sm text-gray-600 mb-3">
                  New here? Check out our databases or visit the Help section for guidance.
                </p>
                <div className="flex items-center gap-2">
                  <a href="#databases" className="btn-primary text-xs py-2 px-4">
                    Explore
                  </a>
                  <button 
                    onClick={dismissOnboarding}
                    className="btn-ghost text-xs py-2 px-4"
                  >
                    Dismiss
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default App;
