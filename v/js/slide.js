/*
 * slider from header in index.php
 */

class SlideSB{

  constructor(slide){ 
    this.slideContainer = document.querySelector(slide.container) 
    this.slideItems = Array.from(document.querySelectorAll(slide.items))
    this.classActive = slide.active
    this.classActiveOld = slide.activeOld
    this.currenItem = this.slideItems[0]
    this.timeOut = slide.time
    this.ready = true
    this.noMobile = slide.noMobile || false
  }


  run(){
    // Interval
    this.animationInterval = setInterval(() => {
      if(this.ready){
        this.ready = false
        this.nextSlide(()=>{
          this.ready = true
          if(this.noMobile)
            this.isMobile()
        });
      }
    },this.timeOut);
  }


  nextSlide(callback){
    
    this.resetClassActive()
    this.currenItem.classList.add(this.classActiveOld)
    this.currenItem = this.whoKeepsOn()
    this.currenItem.classList.add(this.classActive)

    setTimeout(()=>{
      this.resetClassActiveOld()
      callback()
    },this.timeOut / 2);

  }


  observer(){
    document.addEventListener("visibilitychange", ev =>{
      document.visibilityState==="hidden" ? this.stop() : this.run()
    });

    const observer = new IntersectionObserver((entries)=>{
      entries[0].isIntersecting === true  ?  this.ready = true : this.ready = false
    })

    observer.observe(this.slideContainer)
    return this
  }


  whoKeepsOn(){
    const current = this.slideItems.indexOf(this.currenItem)

    if(current < this.slideItems.length-1) //5 - 1 = 4 // 2 < 2
      return this.slideItems[current + 1]
    else if(current == this.slideItems.length-1) // 2==2
      return this.slideItems[0]
  }


  isMobile(){
    if(window.innerWidth<600)
      this.stop()
  }


  resetClassActive(){
    this.slideItems.forEach( e => e.classList.remove(this.classActive))
  }


  resetClassActiveOld(){
    this.slideItems.forEach( e =>{
      if(e !== this.currenItem)
        e.classList.remove(this.classActiveOld);
    })
  }


  stop(){
    clearInterval(this.animationInterval)    
  }
}

const slide = new SlideSB({
  container: ".slide",
  items: ".slide__item",
  active: "is-active",
  activeOld: "is-active--prev",
  time: 6000,
  noMobile: false
});

slide.observer().run()