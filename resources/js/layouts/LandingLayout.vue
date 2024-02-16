<template>
    <div class="container flex flex-col font-sans bg-slate-100 min-h-screen min-w-full">
        <div class="flex justify-center items-center md:h-4/5-screen h-screen min-w-full overflow-hidden relative">
            <div class="palindots flex text-center font-semibold font-sans text-2xl">
                <div class="infinitebg">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>7</span>
                    <span>8</span>
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>7</span>
                    <span>8</span>
                </div>
                <div class="infinitebg">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>7</span>
                    <span>8</span>
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>7</span>
                    <span>8</span>
                </div>
                <div class="infinitebg">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>7</span>
                    <span>8</span>
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>7</span>
                    <span>8</span>
                </div>
            </div>
            <url-input/>
        </div>
    </div>
</template>
<script>
import urlInput from '../components/url-input.vue';

export default {
    components: {
        urlInput
    }
}
</script>
<style>
@keyframes infiniteScroll {
  0% { transform: translateY(0); }
  100% { transform: translateY(-100%); }
}

.palindots {
    position: absolute;
    transform: rotate3d(2.5,2,1.5,-0.15turn);
}

.palindots > .infinitebg:nth-child(odd) {
    background-color: chartreuse;
    width: 20vw;
}
.palindots > .infinitebg:nth-child(even){
    background-color: aqua;
    width: 20vw;
}

.infinitebg > span {
    display: block;
    padding: 1rem 0 1rem 0;
}
</style>